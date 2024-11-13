<?php
// Incluimos el archivo connection.
require_once 'Config/Connection.php';

class User
{
    // Propiedad de la tabla. 
    private static $columnas = [];
    private static $objPDO = null;


    /**
     * TODO: Método que retornara un filtrado de 10 usuarios.
     * Para que la petición a la base de datos no le afecte el rendimiento.
     *
     * @return array
     */
    public static function get_all_User($page = 1)
    {
        self::$objPDO = Connection::instanceObject()->connectDatabase();
        $stament = self::$objPDO->prepare('SELECT * FROM user WHERE id BETWEEN ? AND ? ');
        $stament->bindValue(1, $page, PDO::PARAM_INT);
        $stament->bindValue(2, $page * 10, PDO::PARAM_INT);
        $stament->execute();
        return $stament->fetchAll();
    }

    public static function get_one_User($id)
    {

        return [];
    }


    public static function get_filtret_User($primerFiltrer, $second)
    {
        return [];
    }
}
