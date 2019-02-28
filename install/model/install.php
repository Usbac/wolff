<?php

class Model_install {

    public function error($data) {
        $this->db = @new mysqli($data['host'], $data['username'], $data['password'], $data['db']);
        return $this->db->connect_error;
    }

}