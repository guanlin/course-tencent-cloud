<?php

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Repos\User as UserRepo;
use App\Validators\CourseUser as CourseUserValidator;

class CourseStudent extends Service
{

    public function getCourse($courseId)
    {
        $repo = new CourseRepo();

        return $repo->findById($courseId);
    }

    public function getStudent($userId)
    {
        $repo = new UserRepo();

        return $repo->findById($userId);
    }

    public function getCourseStudents()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['role_type'] = CourseUserModel::ROLE_STUDENT;
        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseUserRepo = new CourseUserRepo();

        $pager = $courseUserRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourseStudents($pager);
    }

    public function getCourseLearnings()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $learningRepo = new LearningRepo();

        $pager = $learningRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourseLearnings($pager);
    }

    public function getCourseStudent($courseId, $userId)
    {
        $result = $this->findOrFail($courseId, $userId);

        return $result;
    }

    public function createCourseStudent()
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $data = [
            'role_type' => CourseUserModel::ROLE_STUDENT,
            'source_type' => CourseUserModel::SOURCE_IMPORT,
        ];

        $data['course_id'] = $validator->checkCourseId($post['course_id']);
        $data['user_id'] = $validator->checkUserId($post['user_id']);
        $data['expire_time'] = $validator->checkExpireTime($post['expire_time']);

        $validator->checkIfJoined($post['course_id'], $post['user_id']);

        $courseUser = new CourseUserModel();

        $courseUser->create($data);

        $this->updateUserCount($data['course_id']);

        return $courseUser;
    }

    public function updateCourseStudent()
    {
        $post = $this->request->getPost();

        $courseStudent = $this->findOrFail($post['course_id'], $post['user_id']);

        $validator = new CourseUserValidator();

        $data = [];

        if (isset($post['expire_time'])) {
            $data['expire_time'] = $validator->checkExpireTime($post['expire_time']);
        }

        if (isset($post['locked'])) {
            $data['locked'] = $validator->checkLockStatus($post['locked']);
        }

        $courseStudent->update($data);

        return $courseStudent;
    }

    protected function updateUserCount($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $updater = new CourseStatsUpdater();

        $updater->updateUserCount($course);
    }

    protected function findOrFail($courseId, $userId)
    {
        $validator = new CourseUserValidator();

        $result = $validator->checkCourseStudent($courseId, $userId);

        return $result;
    }

    protected function handleCourseStudents($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseUserListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

    protected function handleCourseLearnings($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new LearningListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleChapters($pipeB);
            $pipeD = $builder->handleUsers($pipeC);
            $pipeE = $builder->arrayToObject($pipeD);

            $pager->items = $pipeE;
        }

        return $pager;
    }

}