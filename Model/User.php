<?php
// Incluimos el archivo connection.
require_once 'Config/Connection.php';

class User
{
    // Propiedad de la tabla. 
    private static $columnas = [
        'id',
        'nombre',
        'apellido_Paterno',
        'apellido_Materno',
        'correo',
        'password',
        'token',
        'tokenActivado',
        'rol'
    ];


    /**
     * TODO: Método que retornara un filtrado de 10 máximo de usuarios por pagina.
     * Para que la petición a la base de datos no le afecte el rendimiento.
     *
     * @return array
     */
    public static function get_all_User($page = 1) : array
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT '. implode(', ', self::$columnas) . ' FROM user WHERE id BETWEEN ? AND ? ');
        // Agregamos los valores en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $page, PDO::PARAM_INT);
        $stament->bindValue(2, $page * 10, PDO::PARAM_INT);
        // Mandamos a ejecutar el query.
        $stament->execute();
        // Retornamos la respuesta.
        return $stament->fetchAll();
    }

    /**
     * TODO: Método que obtiene un usuario en especifico.
     *
     * @param [type] $id
     * @return array
     */
    public static function get_one_User($id) : array
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT '. implode(', ', self::$columnas) . ' FROM user WHERE id = ?');
        // Sustituimos el valor en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $id, PDO::PARAM_INT);
        // Mandamos a ejecutar el query.
        $stament->execute();
        // Almaceno la respuesta si no encuentra nada entonces retornar un boolean.
        $valor = $stament->fetch(PDO::FETCH_ASSOC);
        // Si el valor es false entonces entrara aquí.
        if ($valor) {
            // Retornara un array.
            return [];
        }
        // Retornamos la respuesta.
        return $valor;
    }

    /**
     * TODO: Método que filtrara por columna.
     *
     * @param [type] $columnFiltrer
     * @param [type] $startFilter
     * @param [type] $endFilter
     * @return array
     */
    public static function get_filtret_User($pageFilter , $columnFiltrer) : array
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT '. implode(', ', self::$columnas) . ' FROM user WHERE id BETWEEN ? AND ? ORDER BY ? ASC');
        // Agregamos los valores en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $pageFilter, PDO::PARAM_INT);
        $stament->bindValue(2, $pageFilter * 10, PDO::PARAM_INT);
        $stament->bindValue(3, $columnFiltrer, PDO::PARAM_STR);
        // Mandamos a ejecutar el query.
        $stament->execute();
        // Retornamos los valores
        return $stament->fetchAll();;
    }
}
