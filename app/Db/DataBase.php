<?php

namespace App\Db;
    use \PDO;
    use \PDOException;

class DataBase{

    const Host = 'localhost';
    const Name = 'wdev_vagas';
    const User = 'root';
    const Passw = '';

    //Nome da Tabela a ser Manipulada
    private $table;

    //Instancia de Conexão com o Banco de Dados
    //@var PDO
    private $connection;

    public function __construct($table = null){

        $this->table = $table;
        $this->setConnection();
    }
    //Metodo responsavel por criar uma conexao com o banco de dados
    private function setConnection(){

        try{
            $this->connection = new PDO('mysql:host='.self::Host.';dbname='.self::Name,self::User,self::Passw);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//Erro pra trava o Sistema
        }catch(PDOException $e){
            die('ERROR: ' .$e->getMessage());
        }

    }  
    /**
     * Metodos responsavel por execultar queries dentro do Banco de Dados
     * @param String $query
     * @param array $params
     * @return PDOStatement
     */                             //serao os valores substituido nos $binds
    public function execute($query, $params = []){
        try{
            $statement = $this ->connection-> prepare($query);
            $statement->execute($params);
            return $statement;
         }catch(PDOException $e){
            die('ERROR: ' .$e->getMessage());
        }
    }

    /*Metodo responsavel por Inserir Dados no Banco
    @Param array $values[field => value]
    @return interger (ID Inserido)
    */
    public function insert($values){
        //ex de query comum!!
       // $query = 'INSERT INTO vagas (titulo,descricao,ativo,data) VALUES('teste','teste2', 's','20200824')'
       
       //PDO!! Tudo que for Dinamico usar dessa Forma!!!!                                                 --Passa como PARAMETRO
       // $query = 'INSERT INTO vagas (titulo,descricao,ativo,data) VALUES('?','?','?','?')'
       
    
        //DADOS DA 
                //-
        $fields = array_keys($values);
                //-funcao- se tiver x posições, se ele não tiver x posiçoes ira criar uma posiçoes com o valor especifico
        $binds = array_pad([],count($fields),'?');

        //MONTAR QUERY
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

      //EXECUTA O INSERT
      $this->execute($query,array_values($values));

      //RETORNA O ID INSERIDO
      return $this->connection->lastInsertId();
    }

   /**
 * Metodo responsavel por obter a CONSULTA do Banco de Dados
 * @param String $where
 * @param String $order
 * @param String $limit
 * @param String $fields
 * @return PDOStatement
  */                                                                 //Paramentros para buscar todos os Dados
//     public function select($where = null, $order=null, $limit=null, $fields = '*'){
//        //$query = 'SELECT * FROM vagas WHERE...ORDER BY... LIMIT..';
       
       
//         //DADOS DA QUERY
//         $where = strlen($where) ? 'WHERE'.$where : '';
//         $order = strlen($order) ? 'ORDER'.$order : '';
//         $limit = strlen($limit) ? 'LIMIT'.$limit : '';
        
//         //MONTA A QUERY
//         //$query = 'SELECT * FROM '.$this->table. ' '.$where. ' '.$order. ' ' .$limit;
//         $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

//         //EXECUTA A QUERY
//         return $this->execute($query);

        
//     }

public function select($where = null, $order = null, $limit = null, $fields = '*'){
    //DADOS DA QUERY
    $where = strlen($where) ? 'WHERE '.$where : '';
    $order = strlen($order) ? 'ORDER BY '.$order : '';
    $limit = strlen($limit) ? 'LIMIT '.$limit : '';

    //MONTA A QUERY
    $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

    //EXECUTA A QUERY
    return $this->execute($query);
  }

    // public function update($where, $values){
    //     //DADOS DA QUERY
    //         $fields = array_keys($values);

    //         //MONTA QUERY
    //         $query = 'UPDATE ' .$this->table. ' SET ' .implode('=?, ',$fields).'=? WHERE'.$where;

    //        // $query = 'UPDATE' .$this->table. ' SET Titulo =?, Descricao=?, WHERE'.$where;

    //        //EXECUTAR A QUERY

    //        $this->execute($query, array_values($values));

    //        return true;

    // }


    public function update($where,$values){
        //DADOS DA QUERY
        $fields = array_keys($values);
    
        //MONTA A QUERY
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;
    
        //EXECUTAR A QUERY
        $this->execute($query,array_values($values));
    
        //RETORNA SUCESSO
        return true;
      }

    public function delete($where){
    
              //MONTA QUERY
              $query = 'DELETE FROM ' .$this->table. ' WHERE  '.$where;
  
            //
             $this->execute($query);
  
             return true;

    }



}
