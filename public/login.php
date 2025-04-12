<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT ID, StuID, StudentName FROM tblstudent WHERE StudentEmail = :email AND Password = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        // Login successful
        $_SESSION['sturecmsuid'] = $result->ID;
        $_SESSION['sturecmsstuid'] = $result->StuID;
        $_SESSION['studentname'] = $result->StudentName;

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid login details');</script>";
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="h-screen m-0 p-0 overflow-hidden">
    <!-- Vanta Background Container -->
    <div id="vanta-bg" class="h-full w-full flex items-center justify-center">
      <!-- Login Card -->
      <div class="w-full max-w-md rounded-xl bg-[#0e0e10] p-8 shadow-2xl border border-gray-800 backdrop-blur-md">
        <h2 class="mb-6 text-center text-3xl font-bold text-white">
          Login to <span class="text-yellow-400">Padhle!</span>
        </h2>

        <button
          onclick="signInWithGoogle()"
          class="flex w-full my-2 items-center justify-center gap-2 rounded-lg bg-blue-1000 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 transition duration-200">
          <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="h-5 w-5" alt="Google logo" />
          Continue with Google
        </button>

        <form action="login.php" method="POST" class="space-y-4">
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-white">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your Email"
              class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-white">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your Password"
              class="mt-1 w-full rounded-lg my-2 bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full rounded-lg bg-white py-3 font-semibold text-black shadow-lg transition-all duration-300 hover:bg-red-700">
            Continue
          </button>
        </form>
      </div>
    </div>

    <!-- Firebase SDKs -->
    <script type="module">
      import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
      import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-auth.js";
      import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-analytics.js";

      const firebaseConfig = {
        apiKey: "AIzaSyBf2FyvjgVyxyYHUCDg-p5Pf2d9q3Pczjs",
        authDomain: "padhle-student-portal.firebaseapp.com",
        projectId: "padhle-student-portal",
        storageBucket: "padhle-student-portal.firebasestorage.app",
        messagingSenderId: "904918298191",
        appId: "1:904918298191:web:9bb8065fa7e9089bff843a",
        measurementId: "G-ZWZVR1SV4H"
      };

      const app = initializeApp(firebaseConfig);
      const auth = getAuth(app);
      const provider = new GoogleAuthProvider();

      window.signInWithGoogle = () => {
        signInWithPopup(auth, provider)
          .then((result) => {
            const user = result.user;
            console.log("✅ Signed in as:", user.displayName);
            window.location.href = "dashboard.html";
          })
          .catch((error) => {
            console.error("❌ Sign-in error:", error.message);
          });
      };
    </script>

    <!-- Vanta Background Scripts -->
    <script src="../src/assets/p5.min.js"></script>
    <script src="../src/assets/vanta.topology.min.js"></script>
    <script>
      VANTA.TOPOLOGY({
        el: "#vanta-bg",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0xeb0e0e,
        backgroundColor: 0x000000
      });
    </script>
  </body>
</html>