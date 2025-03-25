<?php

namespace Model;

use Config\Connection;
use Errors\Error;
use PDO;
use Exception, PDOException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
     * @param integer $page
     * @return array
     */
    public static function get_all_User(int $page = 1): array
    {
        try {
            // Obtenemos el objeto PDO
            $objPDO = Connection::instanceObject()->connectDatabase();
            // Validamos que el query sea correcto syntax.
            // Agregamos las columnas dinámicamente.
            $stament = $objPDO->prepare('SELECT ' . implode(', ', self::$columnas) . ' FROM user WHERE id BETWEEN ? AND ? ');
            // Agregamos los valores en la posición correcta o sea en el carácter '?'
            $stament->bindValue(1, ($page - 1) * 10, PDO::PARAM_INT);
            $stament->bindValue(2, $page * 10, PDO::PARAM_INT);
            // Mandamos a ejecutar el query.
            $stament->execute();
            // Retornamos la respuesta.
            return $stament->fetchAll();
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
    }

    /**
     * TODO: Método que obtiene un usuario en especifico.
     *
     * @param integer $id
     * @return array
     */
    public static function get_one_User(int $id): array
    {
        try {
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
            // Retornamos la respuesta.
            return $valor ?: [];
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
    }

    /**
     * TODO: Método que filtrara por columna.
     *
     * @param integer $pageFilter
     * @param string $columnFiltrer
     * @return array
     */
    public static function get_filtret_User(int $pageFilter, string $columnFiltrer): array
    {
        try {
            if (!in_array($columnFiltrer, self::$columnas)) {
                throw new Exception("Columna no permitida para ordenar.");
            }
            // Obtenemos el objeto PDO
            $objPDO = Connection::instanceObject()->connectDatabase();
            // Validamos que el query sea correcto syntax.
            // Agregamos las columnas dinámicamente.
            $stament = $objPDO->prepare('SELECT ' . implode(', ', self::$columnas) . ' FROM user WHERE id BETWEEN ? AND ? ORDER BY ' . $columnFiltrer .  ' ASC');
            // Agregamos los valores en la posición correcta o sea en el carácter '?'
            $stament->bindValue(1, ($pageFilter - 1) * 10, PDO::PARAM_INT);
            $stament->bindValue(2, $pageFilter * 10, PDO::PARAM_INT);
            // Mandamos a ejecutar el query.
            $stament->execute();
            // Retornamos los valores
            return $stament->fetchAll();
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
    }


    /**
     * TODO: Crear un nuevo dato de usuario.
     *
     * @param array $valoresEnviadosPeticion
     * @return string
     */
    public static function insertUser(array $valoresEnviadosPeticion): string
    {
        try {
            // Llamo a método para validar si existen los parámetros 
            self::validarParametros($valoresEnviadosPeticion);
            // Obtenemos el objeto PDO
            $objPDO = Connection::instanceObject()->connectDatabase();
            // Mandar a validar si existe el correo electrónico
            $objUser = self::existeEmail($valoresEnviadosPeticion[3], $objPDO);
            // Si existe la propiedad 'id' en objUser entonces dirá que ya existe
            if (isset($objUser['id'])) {
                throw new Exception( Error::$tipoError['correoExistente']['mensaje'] , Error::$tipoError['correoExistente']['code'] );
            }
            // Mandar a encriptar password
            $passwordEncriptada = self::encriptar($valoresEnviadosPeticion[4]);
            // Validamos que el query sea correcto syntax.
            // Agregamos las columnas dinámicamente.
            $stament = $objPDO->prepare('INSERT INTO user (' . self::$columnas[1] . ' , ' . self::$columnas[2] . ' , ' .
                self::$columnas[3] . ' , ' . self::$columnas[4] . ' , ' . self::$columnas[5] . ' , ' . self::$columnas[6] . ') VALUES ( ?, ?, ?, ?, ?, ?)');
            $stament->bindValue(1, $valoresEnviadosPeticion[0], PDO::PARAM_STR);
            $stament->bindValue(2, $valoresEnviadosPeticion[1], PDO::PARAM_STR);
            $stament->bindValue(3, $valoresEnviadosPeticion[2], PDO::PARAM_STR);
            $stament->bindValue(4, $valoresEnviadosPeticion[3], PDO::PARAM_STR);
            $stament->bindValue(5, $passwordEncriptada, PDO::PARAM_STR);
            $stament->bindValue(6, $valoresEnviadosPeticion[5], PDO::PARAM_STR);
            // Ejecutar el Query.
            $stament->execute();
            // llamamos al método generar token.
            return self::generarToken($objPDO->lastInsertId());
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
    }

    private static function generarToken($id_usuario): string
    {
        $now = strtotime("now"); // Fecha actual.
        $payload = [
            'exp' => $now + 3600, // Le estoy diciendo que espire en una hora
            'iat' => $now,
            'data' => $id_usuario // El id.
        ];
        $key = $_ENV['KEY']; // Aquí sera la llave con la cual se crearan los tokens.
        return JWT::encode($payload, $key, 'HS256');
    }

    /**
     * TODO: Método para encriptar
     *
     * @param string $passwordNoEncriptada
     * @return string
     */
    private static function encriptar(string $passwordNoEncriptada): string
    {
        // * Retornamos el password encriptado
        return password_hash($passwordNoEncriptada, PASSWORD_BCRYPT);
    }

    /**
     * TODO: Método que retorna un boolean si la contraseña es correcta.
     *
     * @param [type] $contrasenia_ingresada
     * @param [type] $hash_guardado
     * @return boolean
     */
    private static function descriptar($contrasenia_ingresada, $hash_guardado): bool
    {
        return password_verify($contrasenia_ingresada, $hash_guardado);
    }

    /**
     * TODO: Método para autenticar
     *
     * @param array $params
     * @return string
     */
    public static function autenticacion(array $params): string
    {
        // Validar si me enviaron los parámetros contraseña y correo
        if (!isset($params['correo_electronico']) || !isset($params['contrasenia'])) {
            throw new Exception( Error::$tipoError['passwordYCorreoFaltan']['mensaje'] , Error::$tipoError['passwordYCorreoFaltan']['code'] );
        }
        $objPDO = Connection::instanceObject()->connectDatabase();

        $objUser = self::existeEmail($params['correo_electronico'], $objPDO);
        // Si no existe enviamos no existe el correo electrónico
        if (!isset($objUser['id'])) {
            throw new Exception( Error::$tipoError['correoNoExiste']['mensaje'] , Error::$tipoError['correoNoExiste']['code'] );
        }
        
        // Validar que sea la contraseña correcta
        self::descriptar($params['contrasenia'], $objUser['password']);

        // Aquí obtener el token y enviárselo.
        return self::generarToken($objUser['id']);
    }

    /**
     * TODO: Validar si existe ya ese correo en la base de datos.
     *
     * @param string $emailValidar
     * @param [type] $objPDO
     * @return array
     */
    private static function existeEmail(string $emailValidar, $objPDO): array
    {
        // Validamos que el query sea correcto syntax.
        // Agregamos las columnas dinámicamente.
        $stament = $objPDO->prepare('SELECT * FROM user WHERE ' . self::$columnas[4] . ' = ?');
        // Agregamos los valores en la posición correcta o sea en el carácter '?'
        $stament->bindValue(1, $emailValidar, PDO::PARAM_STR);
        // Mandamos a ejecutar el query.
        $stament->execute();
        // Almaceno la respuesta si no encuentra nada entonces retornar un boolean.
        $valor = $stament->fetch(PDO::FETCH_ASSOC);
        return is_array($valor) ? $valor : [];
    }

    /**
     * TODO: Método para eliminar un user.
     *
     * @param [type] $id
     * @return bool
     */
    public static function delete_user($id): bool
    {
        try {
            // Obtenemos el objeto PDO
            $objPDO = Connection::instanceObject()->connectDatabase();
            // Validamos que el query sea correcto syntax.
            // Agregamos las columnas dinámicamente.
            $stament = $objPDO->prepare('DELETE FROM user WHERE id = ?');
            // Agregamos los valores en la posición correcta o sea en el carácter '?'
            $stament->bindValue(1, $id, PDO::PARAM_INT);
            // Mandamos a ejecutar el query.
            return $stament->execute();
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
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
        try {
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
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
    }

    /**
     * TODO: Método para modificar un usuario
     *
     * @param [type] $valoresEnviadosPeticion
     * @return boolean
     */
    public static function updateUser(array $valoresEnviadosPeticion): bool
    {
        try {
            self::validarParametros($valoresEnviadosPeticion);
            // Obtenemos el objeto PDO
            $objPDO = Connection::instanceObject()->connectDatabase();
            // Mandar a validar si existe el correo electronico
            if (isset(self::existeEmail($valoresEnviadosPeticion[4], $objPDO)['id'])) {
                throw new Exception("Ya existe el correo", 1);
            }
            // * Encriptar la nueva contraseña
            $passwordEncriptado = self::encriptar($valoresEnviadosPeticion[5]);
            // Validamos que el query sea correcto syntax.
            // Agregamos las columnas dinámicamente.
            $stament = $objPDO->prepare('UPDATE user SET ' . self::$columnas[1] . ' = ?, ' . self::$columnas[2] . ' = ?, ' . self::$columnas[3] . ' = ?, ' .
                self::$columnas[4] . ' = ?, ' . self::$columnas[5] . ' = ?, ' . self::$columnas[6] . ' = ? WHERE id = ?');
            $stament->bindValue(1, $valoresEnviadosPeticion[1], PDO::PARAM_STR);
            $stament->bindValue(2, $valoresEnviadosPeticion[2], PDO::PARAM_STR);
            $stament->bindValue(3, $valoresEnviadosPeticion[3], PDO::PARAM_STR);
            $stament->bindValue(4, $valoresEnviadosPeticion[4], PDO::PARAM_STR);
            $stament->bindValue(5, $passwordEncriptado, PDO::PARAM_STR);
            $stament->bindValue(6, $valoresEnviadosPeticion[6], PDO::PARAM_STR);
            $stament->bindValue(7, $valoresEnviadosPeticion[0], PDO::PARAM_INT);
            // Mandamos a ejecutar el query.
            return $stament->execute();
        } catch (PDOException $error) {
            throw new Exception( Error::$tipoError['errorSintaxis']['mensaje'], Error::$tipoError['errorSintaxis']['code'] );
        }
    }

    /**
     * TODO: Validar columnas de la tabla que me los hayan enviado
     *
     * @param array $params
     * @return void
     */
    public static function validarParametros(array $params)
    {
        // Verificar si las columnasDeLaTabla las enviaron como parámetros.
        foreach (self::$columnas as $columna) {
            // Si no esta definido dentro del arreglo que me enviaron entonces marco el error y lo envió.
            if (!isset($params->$columna)) {
                // Paso el arreglo a una cadena
                $mensajeError = "Las columnas son las siguientes: " . implode(', ', self::$columnas);
                // ! Error por default.
                throw new Exception($mensajeError, 404);
            }
        }
    }
}
