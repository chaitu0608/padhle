<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php'); // You’ll create this file in the next step
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Padhle</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />

    <!-- Custom Stylesheet -->
    <link href="../src/assets/styles.css" rel="stylesheet" />

    <!-- Acertinity UI Meteor Effect -->
    <script src="https://cdn.acertinityui.com/meteor.min.js"></script>
  </head>
  <body class="bg-black text-white">
    <!-- Meteor Background -->
    <canvas
      id="meteor-canvas"
      class="absolute top-0 left-0 -z-10 h-full w-full"
    ></canvas>

    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>
    <!-- Main Content -->
    <div class="relative z-10">
      <nav
        class="flex w-full items-center justify-between bg-[#1E1E1E] px-10 py-5 shadow-lg"
      >
        <div class="flex items-center space-x-4">
          <a href="https://www.somaiya.edu/en/">
            <img
              src="../public/images/university-logo.svg"
              alt="College Logo"
              class="h-12"
            />
          </a>
          <h2 class="text-xl font-semibold">
            K.J. Somaiya College of Engineering
          </h2>
        </div>
        <div class="flex items-center space-x-8 text-lg font-medium">
          <ul class="flex space-x-8">
            <li>
              <a href="https://www.somaiya.edu/en/" class="hover:text-gray-400"
                >Home</a
              >
            </li>
            <li>
              <a
                href="https://lms-kjsce.somaiya.edu/fmss/"
                class="hover:text-gray-400"
                >FMSS</a
              >
            </li>
            <li>
              <a
                href="https://www.somaiya.edu/en/history/"
                class="hover:text-gray-400"
                >About</a
              >
            </li>
            <li>
              <a
                href="https://www.somaiya.edu/en/contact-us/"
                class="hover:text-gray-400"
                >Contact</a
              >
            </li>
          </ul>
          <a href="https://somaiya.com/en">
            <img
              src="../public/images/Trust.svg"
              alt="Somaiya Trust Logo"
              class="h-12"
            />
          </a>
        </div>
      </nav>

      <!-- Hero Section with Somaiya Photo -->
      <div class="hero bg-cover bg-center py-20 text-center text-white">
        <h1 class="text-5xl font-extrabold drop-shadow-lg">
          Study Smart, Not Hard. <span class="text-yellow-400">Padhle</span> is
          Here.
        </h1>
        <p class="mt-4 text-xl text-gray-400">
          Your One-Stop Solution for All College Essentials.
        </p>
      </div>

      <div class="relative mt-20 flex justify-center space-x-10">
        <div
          class="magic-card rounded-lg bg-[#1E1E1E] p-6 text-center shadow-lg"
        >
          <h3 class="text-2xl font-semibold text-white">📚 Student Login</h3>
          <p class="mt-2 text-gray-300">
            Access your courses and track progress.
          </p>
          <a href="./login.php"><button type="button" class="magic-btn">Login as Student</button></a>
        </div>
        <div class="magic-card">
          <h3 class="text-2xl font-semibold text-white">🗓️ Admin Login</h3>
          <p class="mt-2 text-gray-300">
            Manage students, schedules, and more.
          </p>
          <a href="./admin/login.php"><button type="button" class="magic-btn">Login as Admin</button></a>
        </div>
      </div>
      <div class="myFooter px-10 pt-20">
        <footer class="rounded-t-lg bg-[#121212] py-8 text-white">
          <p class="text-center text-sm text-gray-500">
            © SOMAIYA Vidyavihar University, Inc. All rights reserved.
          </p>
          <p class="mt-2 text-center text-sm text-gray-500">
            Made with <span class="text-red-500">❤️</span> by Bhoumish and
            Chaitanya
          </p>
        </footer>
      </div>
    </div>

    <!-- Initialize Meteor Effect -->
    <script>
      // Initialize the meteor effect on the canvas
      Meteor.init({
        canvas: document.getElementById("meteor-canvas"),
        meteorColor: "#ffffff",
        backgroundColor: "#000000",
        speed: 2,
        density: 100,
      });
    </script>

    <!-- Custom Script -->
    <script src="../src/assets/script.js"></script>
  </body>
</html>
