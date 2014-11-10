<?php
/**
 * 博客系统
 */
class BlogController  extends BaseController
{
	/**
	 * 主页
	 */
	public function getIndex()
	{
		return '博客主页';
	}

	/**
	 * 新建
	 */
	public function getCreate()
	{
		return '新建页面';
	}

	/**
	 *  保存新建
	 */
	public function postCreate()
	{
		return '保存新建';
	}


	/**
	 * 修改页面
	 */
	public function getEdit($blog_id = 0)
	{
		return '修改页面'.$blog_id;
	}

	/**
	 * 提交修改
	 */
	public function postEdit($blog_id = 0)
	{
		return '提交修改';
	}

	/**
	 * 删除
	 */
	public function postDel()
	{
		return '删除blog';
	}

}

?>