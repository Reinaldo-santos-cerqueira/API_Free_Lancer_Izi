<?php

    class Client{
        private $id = 0;
        private $name = null;
        private $email = null;
        private $password = null;
        private $phone = null;
        private $type = null;
        private $age = null;
        private $profession = null;
        private $documentation = null;

        public function setId( int $id) : void
        {
            $this->id    = $id;
        }

        public function getId() :int 
        {
            return $this->id;
        }
        public function setName( string $name) : void
        {
            $this->name    = $name;
        }

        public function getName() :string
        {
            return $this->name;
        }

        public function setAge( int $age ) :void
        {
            $this->age    = $age;
        }

        public function getAge() :int
        {
            return $this->age;
        }

        public function setEmail( string $email ) :void
        {
            $this->email    = $email;
        }

        public function getEmail() :string
        {
            return $this->email;
        }
        public function setPassword( string $password ) :void
        {
            $this->password = $password;
        }

        public function getPassword() :string
        {
            return $this->password;
        }

        public function setPhone( int $phone ) :void
        {
            $this->phone    = $phone;
        }

        public function getPhone() :int
        {
            return $this->phone;
        }

        public function setType( string $type ) :void
        {
            $this->type = $type;
        }

        public function getType() :string
        {
            return $this->type;
        }

        public function setProfession( string $profession ) :void
        {
            $this->profession = $profession;
        }

        public function getProfession() :string
        {
            return $this->profession;
        }

        public function setDocumentation( int $documentation ) :void
        {
            $this->documentation    = $documentation;
        }

        public function getDocumentation() :int
        {
            return $this->documentation;
        }
        private function connection() :\PDO
        {
            return new PDO('mysql: host = localhost;dbname=freelancerizi','root','');
        }
        

        public function create(){
            $data   =   json_decode(file_get_contents("php://input"),true);
            $this->setName($data['name']);
            $this->setEmail($data['email']);
            $this->setAge($data['age']);
            $this->setPassword($data['password']);
            $this->setPhone($data['phone']);
            $this->setType($data['type']);
            $this->setDocumentation($data['documentation']);
            $this->setProfession($data['profession']);

            $con = $this->connection();
            $sqltext = "INSERT INTO person (name,age,profession,phone,email,password,type,documentation) VALUES(:_name,:_age,:_profession,:_phone,:_email,:_password,:_type,:_documentation)";
            $sqlcon = $con->prepare($sqltext);
            $sqlcon->bindValue(':_name', $this->getName(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_age', $this->getAge(), \PDO::PARAM_INT);
            $sqlcon->bindValue(':_email', $this->getEmail(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_profession', $this->getProfession(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_phone', $this->getPhone(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_password', $this->getPassword(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_documentation', $this->getDocumentation(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_type', $this->getType(), \PDO::PARAM_STR);

            if($sqlcon -> execute()){
                $this->setId($con->lastInsertId());
                $con = $this->connection();
                $sql = "SELECT * from person ORDER BY id ASC";
                $sql = $con->query($sql);
                $sql -> execute();
        
                $result = array();
        
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row;
                }
                return $result;
            }
        }
        public function read($parameter){
            if($parameter != null){
                $sql = "SELECT * from person where id = ".$parameter[0];

            }else{
                $sql = "SELECT * from person ORDER BY id ASC";
            }
            $con = $this->connection();
            $sql = $con->query($sql);
            $sql -> execute();

            $result = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            if(!$result){
                throw new Exception("Nenhum cliente encontrado!",1);
            }
            return $result;
        }
        public function update(){
            $data   =   json_decode(file_get_contents("php://input"),true);
            $this->setName($data['name']);
            $this->setEmail($data['email']);
            $this->setAge($data['age']);
            $this->setPassword($data['password']);
            $this->setPhone($data['phone']);
            $this->setType($data['type']);
            $this->setDocumentation($data['documentation']);
            $this->setProfession($data['profession']);
            $this->setId($data['id']);

            $con = $this->connection();
            $sqltext = "UPDATE person SET name = :_name,age = :_age,profession = :_profession,phone = :_phone,email = :_email,password = :_password,type = :_type,documentation = :_documentation where id = :_id";
            $sqlcon = $con->prepare($sqltext);
            $sqlcon->bindValue(':_name', $this->getName(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_age', $this->getAge(), \PDO::PARAM_INT);
            $sqlcon->bindValue(':_email', $this->getEmail(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_profession', $this->getProfession(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_phone', $this->getPhone(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_password', $this->getPassword(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_documentation', $this->getDocumentation(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_id', $this->getId(), \PDO::PARAM_INT);
            $sqlcon->bindValue(':_type', $this->getType(), \PDO::PARAM_STR);
            $sqlcon->execute();
            if($sqlcon -> execute()){
                $this->setId($data['id']);
                $con = $this->connection();
                $sql1 = "SELECT * from person WHERE id = :_id";
                $sql = $con->prepare($sql1);
                $sql->bindValue(':_id', $this->getId(), \PDO::PARAM_INT);
                $sql -> execute();
        
                $result = array();
        
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row;
                }
                return $result;
            }
        }
        public function delete($parameter){
            if($parameter != null){
                $sql = "DELETE FROM person WHERE id =".$parameter[0];
                echo json_encode($parameter);
                $con = $this->connection();
                $sql = $con->query($sql);
                $sql -> execute();
                if($sql -> execute()){
                    $this->setId($con->lastInsertId());
                    $con = $this->connection();
                    $sql = "SELECT * from person ORDER BY id ASC";
                    $sql = $con->query($sql);
                    $sql -> execute();
            
                    $result = array();
            
                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                        $result[] = $row;
                    }
                    return $result;
                }
            }else{
                echo json_encode("Informe  um id para fazer a exclusão");
            } 
        }
        public function login(){
            $data   =   json_decode(file_get_contents("php://input"),true);
            $this->setEmail($data['email']);
            $this->setPassword($data['password']);
            $this->setType($data['type']);

            $con = $this->connection();

            $sqlText = "SELECT * from person where email = :_email and password = :_password and type = :_type";
            $sql = $con->prepare($sqlText);
            $sql->bindValue(':_email', $this->getEmail(), \PDO::PARAM_STR);
            $sql->bindValue(':_password', $this->getPassword(), \PDO::PARAM_STR);
            $sql->bindValue(':_type', $this->getType(), \PDO::PARAM_STR);

            $sql -> execute();

            $result = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            if(!$result){
                throw new Exception("Login incorreto",1);
            }
            return $result;
        }
    }
