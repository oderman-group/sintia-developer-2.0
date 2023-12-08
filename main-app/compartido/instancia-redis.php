<?php
include("session-compartida.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");

$redis = RedisInstance::getRedisInstance();

// $userID = 1; 
// $sql = "SELECT * FROM ".BD_GENERAL.".usuarios WHERE institucion=41 AND year='2024'";
// $datosUser = mysqli_query($conexion,$sql);

// if (mysqli_num_rows($datosUser) > 0) {
//     while($userData = mysqli_fetch_assoc($datosUser)){
//         //SET VALUE TO REDIS
//         $redis->set("Users:".$userData['uss_id'], json_encode($userData));
//     }
// }
// // GET DATA FROM REDIS
// $dataFromRedis = $redis->get("Users:".$userID);
// //Check if data
// if ($dataFromRedis !== false) {
//     $userData = json_decode($dataFromRedis, true); // DECODE JSON
//     echo "Datos del usuario con ID $userID recuperados de Redis:<br>";
//     foreach ($userData as $key => $value) {
//         echo "$key: $value<br>";
//     }
// } else {
//     echo "No se encontraron datos en Redis para el usuario con ID $userID.";
// }

// // Obtener todas las claves
$keys = $redis->keys('*');

// Imprimir las claves
foreach ($keys as $key) {
    echo $key . "<br>";
}

// // Obtener todas las claves que coinciden con el patrón "Users:*"
// $userKeys = $redis->keys("Users:*");

// // Verificar si hay claves disponibles
// if (!empty($userKeys)) {
//     echo "Usuarios en Redis:<br>";

//     // Recorrer todas las claves y obtener los datos
//     foreach ($userKeys as $userKey) {
//         $userData = $redis->get($userKey);
//         // $userData = json_decode($userData, true);
//         // echo $userData['uss_nombre'];

//         if ($userData !== false) {
//             $userData = json_decode($userData, true);

//             echo "Datos del usuario con ID {$userData['uss_id']}:<br>";
//             foreach ($userData as $key => $value) {
//                 echo "$key: $value<br>";
//             }
//             echo "<br>";
//         }
//     }
// } else {
//     echo "No se encontraron usuarios en Redis.";
// }

// //EJEMPLO DE PAGINACIÍN
// $perPage = 10;  // Número de registros por página
// $page = isset($_GET['page']) ? intval($_GET['page']) : 1;  // Página actual

// $keys = $redis->keys('MATRI:*');  // Obtener todas las claves que comienzan con "MATRI:"
// $totalRecords = count($keys);  // Total de registros

// // Calcular el índice de inicio y el número de registros a recuperar
// $startIndex = ($page - 1) * $perPage;
// $endIndex = $startIndex + $perPage - 1;

// // Filtrar las claves según el índice de inicio y final
// $paginatedKeys = array_slice($keys, $startIndex, $perPage);

// foreach ($paginatedKeys as $key) {
//     $matData = json_decode($redis->get($key), true);

//     // Imprimir la información deseada
//     echo "ID: {$matData['mat_id']}, Curso: {$matData['gra_nombre']}, Nombre: {$matData['mat_nombres']}" . PHP_EOL ."<br>";
// }

// // Aquí podrías imprimir enlaces de paginación, por ejemplo:
// $totalPages = ceil($totalRecords / $perPage);

// for ($i = 1; $i <= $totalPages; $i++) {
//     echo "<a href='instancia-redis.php?page=$i'>$i</a> ";
// }


//  // comandos
// $redis->flushAll(); // Para borrar todos los datos guardados en redis
// $redis->flushDB(); // Para borrar todos los datos de la bd actual
// $redis->close(); //Para cerrar la conexión de redis