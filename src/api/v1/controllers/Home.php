<?php

class Home extends Controller
{

    function Index()
    {
        $model = $this->model("DocumentModel");
        $model->GetDocument();

    }
}

?>