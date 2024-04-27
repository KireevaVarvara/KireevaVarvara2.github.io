<?php
include('config.php');
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {



  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }



  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['tel'] = !empty($_COOKIE['tel_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date_of_birth'] = !empty($_COOKIE['date_of_birth_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['languages'] = !empty($_COOKIE['languages_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);


  if ($errors['fio']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя корректно. <br> Допустимые символы: А-Я, а-я, A-Z, a-z, 0-9, -, ., запятая, пробел</div>';
  }
  if ($errors['tel']) {
    setcookie('tel_error', '', 100000);
    $messages[] = '<div class="error">Заполните телефон корректно. <br>
     более 5 символов </div>';
}
  if ($errors['email']) {
      setcookie('email_error', '', 100000);
      $messages[] = '<div class="error">Заполните email корректно. <br> Email должен содержать символ "@" </div>';
  }
  if ($errors['date_of_birth']) {
      setcookie('date_of_birth_error', '', 100000);
      $messages[] = '<div class="error">Заполните дату рождения корректно. <br> Дата рождения должна быть записана в формате день/месяц/год. </div>';
  }
  if ($errors['gender']) {
      setcookie('gender_error', '', 100000);
      $messages[] = '<div class="error">Укажите пол. </div>';
  }

  if ($errors['languages']) {
      setcookie('languages_error', '', 100000);
      $messages[] = '<div class="error">Укажите хотя бы один язык. </div>';
  }
  
  if ($errors['bio']) {
      setcookie('bio_error', '', 100000);
      $messages[] = '<div class="error">Заполните биографию корректно. <br> Допустимые символы: А-Я, а-я, A-Z, a-z, 0-9, -, ., запятая, пробел</div>';
  }
  
  if ($errors['checkbox']) {
      setcookie('checkbox_error', '', 100000);
      $messages[] = '<div class="error">Согласитесь с контрактом</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['date_of_birth'] = empty($_COOKIE['date_of_birth_value']) ? '' : $_COOKIE['date_of_birth_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['languages'] = empty($_COOKIE['languages_value']) ? array() :
   unserialize($_COOKIE['languages_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['checkbox'] = empty($_COOKIE['checkbox_value']) ? '' : $_COOKIE['checkbox_value'];

  // Включаем содержимое файла form.php.
  include('form.php');


}
else
{// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['fio']) || !preg_match('/^([а-яА-ЯЁёa-zA-Z_,.\s-]+)$/u', $_POST['fio'])) {
  setcookie('fio_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else{
  setcookie('fio_value', $_POST['fio'], time() + 365 * 24 * 60 * 60);
}

if (empty($_POST['tel']) ||  strlen($_POST['tel']) <= 5) {
  setcookie('tel_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else{
  setcookie('tel_value', $_POST['tel'], time() + 365 * 24 * 60 * 60);
}

if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL )) {
  setcookie('email_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else{
  setcookie('email_value', $_POST['email'], time() + 365 * 24 * 60 * 60);
}

if (empty($_POST['date_of_birth']) || !preg_match('%[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]%', $_POST['date_of_birth'])) {
  setcookie('date_of_birth_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else{
  setcookie('date_of_birth_value', $_POST['date_of_birth'], time() + 365 * 24 * 60 * 60);
}

if (empty($_POST['gender']) || !in_array($_POST['gender'], ['w','m'])) {
  setcookie('gender_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}else {
  if( !in_array($_POST['gender'], ['w','m'])){
      $errors = TRUE;
      setcookie('gender_error', '1', time() + 24 * 60 * 60);
  }
    setcookie('gender_value', $_POST['gender'], time() + 365 * 24 * 60 * 60);
}


if (empty($_POST['languages'])) {
  setcookie('languages_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else{
  foreach ($_POST['languages'] as $language) {
    if(!in_array($language, [1,2,3,4,5,6,7,8,9,10,11])){
      setcookie('languages_error', '1', time() + 24 * 60 * 60);
       $errors = TRUE;
       break;
    }
  }
  $abs=array();
      
      foreach ($_POST['languages'] as $res) {
          $abs[$res-1] = $res;
      }
      setcookie('languages_value', serialize($abs), time() + 365 * 24 * 60 * 60);
}


if (empty($_POST['bio']) || !preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $_POST['bio'])) {
  setcookie('bio_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
 }
 else{
  setcookie('bio_value', $_POST['bio'], time() + 365 * 24 * 60 * 60);
 }


if (empty($_POST['checkbox'])|| $_POST['checkbox']!=1) {
  setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
  setcookie('checkbox_value', '0', time() + 365 * 24 * 60 * 60);
  $errors = TRUE;
}
else{
  setcookie('checkbox_value', '1', time() + 365 * 24 * 60 * 60);
}


if ($errors) {
  header('Location: index.php');
  exit();
}
else{
  setcookie('fio_error', '', 100000);
  setcookie('tel_error', '', 100000);
  setcookie('email_error', '', 100000);
  setcookie('date_of_birth_error', '', 100000);
  setcookie('gender_error', '', 100000);
  setcookie('languages_error', '', 100000);
  setcookie('bio_error', '', 100000);
  setcookie('checkbox_error', '', 100000);
}


// Сохранение в базу данных.


$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

// Подготовленный запрос. Не именованные метки.
try {

  $stmt = $db->prepare("INSERT INTO application (name,tel,email,date_of_birth,gender,bio,checkbox) VALUES 
  (?,?,?,?,?,?,?)");
  $stmt -> execute([$_POST['fio'],$_POST['tel'], $_POST['email'], $_POST['date_of_birth'], $_POST['gender'], $_POST['bio'], $_POST['checkbox']]);
  $id = $db->lastInsertId();
  $stmt = $db->prepare("INSERT INTO app_language (id_app, id_pl) VALUES (?,?)");
    foreach ($_POST['languages'] as $ability) {
          $stmt->execute([$id, $ability]);
        }



 
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}


// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
setcookie('save', '1');
header('Location: ?save=1');
}