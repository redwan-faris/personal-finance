<?php
namespace App\Traits;


trait AuthorizeApiResourceTrait
{
    /**
     * Set middlewares  api resource
     *
     * @param string $modelName
     * @param array @options
     * @return void
     */
    public function authorizeApiResource(string $name, array $options = [])
    {
        $this->middleware("can:".$name.".view", $options)->only("index");
        $this->middleware("can:".$name.".view", $options)->only("show");
        $this->middleware("can:".$name.".create", $options)->only("store");
        $this->middleware("can:".$name.".update", $options)->only("update");
        $this->middleware("can:".$name.".delete", $options)->only("destroy");
    }
}
