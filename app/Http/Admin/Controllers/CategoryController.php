<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Category as CategoryService;

/**
 * @RoutePrefix("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * @Get("/list", name="admin.category.list")
     */
    public function listAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $categoryService = new CategoryService();

        $parent = $categoryService->getParentCategory($parentId);
        $categories = $categoryService->getChildCategories($parentId);

        $this->view->setVar('parent', $parent);
        $this->view->setVar('categories', $categories);
    }

    /**
     * @Get("/add", name="admin.category.add")
     */
    public function addAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $categoryService = new CategoryService();

        $topCategories = $categoryService->getTopCategories();

        $this->view->setVar('parent_id', $parentId);
        $this->view->setVar('top_categories', $topCategories);
    }

    /**
     * @Post("/create", name="admin.category.create")
     */
    public function createAction()
    {
        $categoryService = new CategoryService();

        $category = $categoryService->createCategory();

        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['parent_id' => $category->parent_id]
        );

        $content = [
            'location' => $location,
            'msg' => '创建分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.category.edit")
     */
    public function editAction($id)
    {
        $categoryService = new CategoryService();

        $category = $categoryService->getCategory($id);

        $this->view->setVar('category', $category);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.category.update")
     */
    public function updateAction($id)
    {
        $categoryService = new CategoryService();

        $category = $categoryService->getCategory($id);

        $categoryService->updateCategory($id);

        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['parent_id' => $category->parent_id]
        );

        $content = [
            'location' => $location,
            'msg' => '更新分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.category.delete")
     */
    public function deleteAction($id)
    {
        $categoryService = new CategoryService();

        $categoryService->deleteCategory($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.category.restore")
     */
    public function restoreAction($id)
    {
        $categoryService = new CategoryService();

        $categoryService->restoreCategory($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

}