<?php
    namespace AppBundle;

    class Login{
        private $email;
        private $password;

        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @return mixed
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email)
        {
            $this->email = $email;
        }
        /**
         * @param mixed $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

    }





?>