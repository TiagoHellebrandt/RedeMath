<?php

    class InfoStep {
        private $indice;
        private $tipo;
        private $info;

        function __construct($indice,$info){ // Construtor
            $this->setIndice($indice);
            $this->setInfo($info);
        }

        // Getters & Setters

        // Setters

        public function setIndice($indice){
            $this->indice = $indice;
        }

        public function setTipo($tipo){
            $this->tipo = $tipo;
        }

        public function setInfo($info){
            $this->info = $info;
        }

        // Getters

        public function getIndice(){
            return $this->indice;
        }

        public function getTipo(){
            return $this->tipo;
        }

        public function getInfo(){
            return $this->info;
        }

        public function show(){ // Exibir InfoStep
            return "<div class='infostep".$this->getIndice()."'>".$this->getInfo()."</div>";
        }
    }