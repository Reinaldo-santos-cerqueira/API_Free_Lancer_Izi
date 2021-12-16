<?php

    Class Service{

        private $id;
        private $title;
        private $description;
        private $value;
        private $idPerson;

        public function setId( int $id) : void
        {
            $this->id    = $id;
        }

        public function getId() :int 
        {
            return $this->id;
        }

        public function setIdPerson( int $idPerson) : void
        {
            $this->idPerson    = $idPerson;
        }

        public function getIdPerson() :int 
        {
            return $this->idPerson;
        }

        public function setValue( int $value) : void
        {
            $this->value    = $value;
        }

        public function getValue() :int 
        {
            return $this->value;
        }

        public function setTitle( string $title) : void
        {
            $this->title    = $title;
        }

        public function getTitle() :string
        {
            return $this->title;
        }

        public function setDescription( string $description) : void
        {
            $this->description    = $description;
        }

        public function getDescription() :string
        {
            return $this->description;
        }

        private function connection() :\PDO
        {
            return new PDO('mysql: host = localhost;dbname=freelancerizi','root','');
        }
        public function create(){
            $data   =   json_decode(file_get_contents("php://input"),true);
            $this->setTitle($data['title']);
            $this->setDescription($data['description']);
            $this->setValue($data['value']);
            $this->setIdPerson($data['idPerson']);
            $con = $this->connection();

            $sqlText = "INSERT INTO service (title, description, value, idPerson) VALUES (:_title, :_description, :_value, :_idPerson)";
            $sqlcon = $con->prepare($sqlText);
            $sqlcon->bindValue(':_title',$this->getTitle(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_description',$this->getDescription(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_value',$this->getValue(), \PDO::PARAM_INT);
            $sqlcon->bindValue(':_idPerson',$this->getIdPerson(), \PDO::PARAM_INT);
            if($sqlcon -> execute()){
                $this->setId($con->lastInsertId());
                $con = $this->connection();
                $sql = "SELECT * from service ORDER BY id ASC";
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
                $sql = "SELECT * from service where idPerson = ".$parameter[0];

            }else{
                $sql = "SELECT * from service ORDER BY id ASC";
            }
            $con = $this->connection();
            $sql = $con->query($sql);
            $sql -> execute();

            $result = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
            }
            if(!$result){
                throw new Exception("Nenhum servico encontrado",1);
            }
            return $result;
        }
        public function update(){
            $data   =   json_decode(file_get_contents("php://input"),true);
            $this->setTitle($data['title']);
            $this->setDescription($data['description']);
            $this->setValue($data['value']);
            $this->setId($data['id']);
            $con = $this->connection();
            $sqlText = "UPDATE service SET title=:_title, description=:_description, value=:_value where id = :_id";
            $sqlcon = $con->prepare($sqlText);
            $sqlcon->bindValue(':_title', $this->getTitle(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_description', $this->getDescription(), \PDO::PARAM_STR);
            $sqlcon->bindValue(':_value', $this->getValue(), \PDO::PARAM_INT);
            $sqlcon->bindValue(':_id', $this->getId(), \PDO::PARAM_INT);
            $sqlcon->execute();
            if($sqlcon -> execute()){
                $this->setId($data['id']);
                $con = $this->connection();
                $sql1 = "SELECT * from service WHERE id = :_id";
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
                $sql = "DELETE FROM service WHERE id =".$parameter[0];
                $con = $this->connection();
                $sql = $con->query($sql);
                $sql -> execute();
                if($sql -> execute()){
                    $this->setId($con->lastInsertId());
                    $con = $this->connection();
                    $sql = "SELECT * from service ORDER BY id ASC";
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
        public function searchName($parameter){
            if($parameter != null){
                $sql = "SELECT * from service where title like '%".$parameter[0]."%'";
                $con = $this->connection();
                $sql = $con->query($sql);
                $sql -> execute();
    
                $result = array();
    
                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row;
                }
                if(!$result){
                    throw new Exception("Nenhum servico encontrado",1);
                }
                return $result;

            }

        }
    }

?>