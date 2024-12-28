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
        'rol'
    ];


    /**
     * TODO: Método que retornara un filtrado de 10 máximo de usuarios por pagina.
     * Para que la petición a la base de datos no le afecte el rendimiento.
     *
     * @return array
     */
    public static function get_all_User($page = 1): array
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT ' . implode(', ', self::$columnas) . ' FROM user WHERE id BETWEEN ? AND ? ');
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
    public static function get_one_User($id): array
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT ' . implode(', ', self::$columnas) . ' FROM user WHERE id = ?');
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
    public static function get_filtret_User($pageFilter, $columnFiltrer): array
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT ' . implode(', ', self::$columnas) . ' FROM user WHERE id BETWEEN ? AND ? ORDER BY ? ASC');
        // Agregamos los valores en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $pageFilter, PDO::PARAM_INT);
        $stament->bindValue(2, $pageFilter * 10, PDO::PARAM_INT);
        $stament->bindValue(3, $columnFiltrer, PDO::PARAM_STR);
        // Mandamos a ejecutar el query.
        $stament->execute();
        // Retornamos los valores
        return $stament->fetchAll();;
    }



    public static function insertUser(array $array) {
        // Mandar a encriptar password
        $passwordEncriptada = self::encriptar($array[4]);
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('INSERT INTO user (' . self::$columnas[1] . ' , ' . self::$columnas[2] . ' , ' . 
        self::$columnas[3] . ' , ' . self::$columnas[4] . ' , ' . self::$columnas[5] . ' , ' . self::$columnas[6] . ') VALUES ( ?, ?, ?, ?, ?, ?)');
        $stament->bindValue(1, $array[0], PDO::PARAM_STR);
        $stament->bindValue(2, $array[1], PDO::PARAM_STR);
        $stament->bindValue(3, $array[2], PDO::PARAM_STR);
        $stament->bindValue(4, $array[3], PDO::PARAM_STR);
        $stament->bindValue(5, $passwordEncriptada, PDO::PARAM_STR);
        $stament->bindValue(6, $array[5], PDO::PARAM_STR);

        if ($stament->execute()) {
            return true;
        } else {
            throw new Exception("Error Processing Request", 1);
        }
        
    }

    /**
     * TODO: Método para encriptar
     *
     * @param string $passwordNoEncriptada
     * @return string
     */
    private static function encriptar(string $passwordNoEncriptada): string{
        // * Retornamos el password encriptado
        return password_hash($passwordNoEncriptada, PASSWORD_BCRYPT);
    }

    /**
     * TODO: Método para eliminar un user.
     *
     * @param [type] $id
     * @return bool
     */
    public static function delete_user($id): bool
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('DELETE FROM user WHERE id = ?');
        // Agregamos los valores en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $id, PDO::PARAM_INT);
        // Mandamos a ejecutar el query.
        return $stament->execute();
    }

    /**
     * TODO: Método para eliminar varios usuarios.
     *
     * @param [type] $startId
     * @param [type] $finishId
     * @return bool
     */
    public static function deleteFiltrer($startId, $finishId): bool
    {
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('DELETE FROM user WHERE id >= ? AND id <= ?');
        // Agregamos los valores en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $startId, PDO::PARAM_INT);
        $stament->bindValue(1, $finishId, PDO::PARAM_INT);
        // Mandamos a ejecutar el query.
        return $stament->execute();
    }

    /**
     * TODO: Método para modificar un usuario
     *
     * @param [type] $array
     * @return void
     */
    public static function updateUser($array) { 
        // Obtenemos el objeto PDO
        $objPDO = Connection::instanceObject()->connectDatabase();
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('UPDATE user SET ' . self::$columnas[1] . ' = ?, ' . self::$columnas[2] . ' = ?, ' . self::$columnas[3] . ' = ?, ' . 
        self::$columnas[4] . ' = ?, ' . self::$columnas[5] . ' = ?, ' . self::$columnas[8] . ' = ? WHERE id = ?');
        $stament->bindValue(1, $array[1], PDO::PARAM_STR);
        $stament->bindValue(2, $array[2], PDO::PARAM_STR);
        $stament->bindValue(3, $array[3], PDO::PARAM_STR);
        $stament->bindValue(4, $array[4], PDO::PARAM_STR);
        $stament->bindValue(5, $array[5], PDO::PARAM_STR);
        $stament->bindValue(6, $array[6], PDO::PARAM_STR);
        $stament->bindValue(7, $array[0], PDO::PARAM_INT);
        // Mandamos a ejecutar el query.
        return $stament->execute();
    }
}
