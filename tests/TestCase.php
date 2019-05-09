<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $data;

    public function setUp() : void
    {
        parent::setUp();

        $this->data = $this->getData();
    }

    abstract function getData();

    protected function addCsrfToken()
    {
        $this->data['_token'] = csrf_token();
    }

    protected function removeCsrfToken()
    {
        unset($this->data['_token']);
    }

    protected function overrideData($values)
    {
        foreach ($values as $key => $value) {

            $this->data[$key] = $value;

            if  ($value === null) {
                unset($this->data[$key]);
            }

        }
    }

    protected function getJsonRequest()
    {

        return $this->withHeaders([
            'Accept' => 'Application/json',
        ]);
    }
}
