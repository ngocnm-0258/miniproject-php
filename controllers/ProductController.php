<?php
require_once('models/Product.php');

class ProductController
{
    var Product $model;

    function __construct()
    {
        $this->model = new Product();
    }

    function list(): void
    {
        $data = $this->model->All();
        require_once('views/product/list.php');
    }

    function detail(): void
    {
        $code = $_GET['code'];
        $data = $this->model->find($code);
        require_once('views/product/detail.php');
    }

    function add(): void
    {
        require_once('views/product/add.php');
    }

    function store(): void
    {
        $data = $_POST;
        $file = $_FILES;
        try {
            $status = $this->model->insert($data, $file);
            echo $status;
            if ($status) {
                setcookie('msg', 'Thêm mới thành công', time() + 1);
                header('location: index.php?mod=product');
            } else {
                setcookie('msg', 'Thêm mới thất bại.', time() + 1);
                header('location: index.php?mod=product&act=add');
            }
        } catch (FormValidationException $e) {
            setcookie('msg', $e->getMessage(), time() + 1);
            header('location: index.php?mod=product&act=add');
        }
    }

    function edit(): void
    {
        $code = $_GET['code'];
        $data = $this->model->find($code);
        require_once('views/product/edit.php');
    }

    function update(): void
    {
        $data = $_POST;
        $file = $_FILES;
        echo $data['quantity'];
        try {
            $status = $this->model->update($data, $file);
            if ($status) {
                setcookie('msg', 'Sửa thành công', time() + 1);
                header('location: index.php?mod=product');
            } else {
                setcookie('msg', 'Sửa thất bại.', time() + 1);
                header('location: index.php?mod=product&act=edit&code=' . $data["code"]);
            }
        } catch (FormValidationException $e) {
            setcookie('msg', $e->getMessage(), time() + 1);
            header('location: index.php?mod=product&act=edit&code=' . $data["code"]);
        }
    }

    function delete(): void
    {
        $code = $_GET['code'];
        $status = $this->model->delete($code);
        if ($status) {
            setcookie('msg', 'Xoá thành công', time() + 1);
            header('location: index.php?mod=product');
        } else {
            setcookie('msg', 'Xoá thất bại.', time() + 1);
            header('location: index.php?mod=product&act=list');
        }
    }
}
