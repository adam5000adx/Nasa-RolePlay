<?php
session_start();

// معلومات الاتصال بقاعدة البيانات
$servername = "78.47.204.80";
$username_db = "1244";
$password_db = "98pot9XKt7 ";
$database = "accounts";

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username_db, $password_db, $database);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استلام بيانات النموذج
    $username = $_POST['username'];
    $password = $_POST['password'];

    // التحضير للاستعلام
    $stmt = $conn->prepare("SELECT * FROM players WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // التحقق من كلمة المرور
        if ($password === $user['password']) {
            // تسجيل الدخول ناجح
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            
            // إعادة توجيه المستخدم إلى صفحة رئيسية أو لوحة تحكم
            header("Location: welcome.php"); // تأكد من أن لديك هذه الصفحة
            exit();
        } else {
            echo "<p class='text-danger'>اسم المستخدم أو كلمة المرور غير صحيحة.</p>";
        }
    } else {
        echo "<p class='text-danger'>اسم المستخدم أو كلمة المرور غير صحيحة.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
