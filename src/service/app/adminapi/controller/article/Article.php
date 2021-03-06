<?php

namespace app\adminapi\controller\article;

use app\adminapi\Controller;
use app\adminapi\validate\article\ArticleValidate;
use app\model\article\ArticleModel;
use plum\exception\FailException;
use plum\utils\Attachment;

class Article extends Controller
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function page()
    {
        $page = ArticleModel::with('category')
            ->autoSearch()
            ->autoOrder()
            ->paginate()
            ->toArray();
        Attachment::getItem($page['data'], ['cover']);
        trace('[文章管理] - [分页]','op');
        return $this->success($page);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function create()
    {
        $data = $this->request->param();
        validate(ArticleValidate::class)
            ->scene('create')
            ->check($data);
        Attachment::getId($data['cover']);
        ArticleModel::create($data);
        trace('[文章] - [新增]','op');
        return $this->success('操作成功');
    }

    /**
     * 更新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function update()
    {
        validate(ArticleValidate::class)
            ->scene('update')
            ->check($this->request->param());
        ArticleModel::update($this->request->param());
        trace('[文章] - [更新]','op');
        return $this->success('操作成功');
    }

    /**
     * 详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function detail()
    {
        $detail = ArticleModel::findOrFail($this->request->param('id'));
        Attachment::getItem($detail,['cover']);
        trace('[文章] - [详情]','op');
        return $this->success($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public function delete()
    {
        $detail = ArticleModel::find($this->request->param('id'));
        if (!$detail)
            throw new FailException('操作失败');
        $detail->delete();
        trace('[文章] - [删除]','op');
        return $this->success();
    }
}