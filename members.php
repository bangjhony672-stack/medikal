<?php
include 'db.php';

// Handle Form Submission
$message = "";
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $hobby = mysqli_real_escape_string($conn, $_POST['hobby']);
    
    // Photo Upload Logic
    $photo_name = $_FILES['photo']['name'];
    $photo_tmp = $_FILES['photo']['tmp_name'];
    $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
    $new_photo_name = time() . '_' . uniqid() . '.' . $photo_ext;
    $upload_dir = 'assets/img/members/';
    
    if (move_uploaded_file($photo_tmp, $upload_dir . $new_photo_name)) {
        $query = "INSERT INTO members (name, hobby, photo) VALUES ('$name', '$hobby', '$new_photo_name')";
        if (mysqli_query($conn, $query)) {
            $message = "Pendaftaran berhasil!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Gagal mengunggah foto.";
    }
}

// Fetch Members
$result = mysqli_query($conn, "SELECT * FROM members ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Member - Medikal Underwater</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'rara-teal': '#00B4D8',
                        'rara-dark': '#1A1A1A',
                        'rara-gray': '#333333',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        .font-heavy { font-weight: 800; }
    </style>
</head>
<body class="bg-gray-50 text-rara-dark font-sans">

<!-- BEGIN: Navbar -->
<header class="bg-white/80 backdrop-blur-lg sticky top-0 z-50 shadow-sm">
  <div class="container mx-auto px-4 flex justify-between items-center py-4">
    <a href="index.html" class="text-2xl font-heavy text-rara-dark">
      MEDIKAL <span class="text-rara-teal">UNDERWATER</span>
    </a>
    <nav class="hidden lg:flex items-center gap-8">
      <a href="about.html" class="font-semibold hover:text-rara-teal transition-colors">Tentang Kami</a>
      <a href="programs.html" class="font-semibold hover:text-rara-teal transition-colors">Program</a>
      <a href="coaches.html" class="font-semibold hover:text-rara-teal transition-colors">Coach</a>
      <a href="schedule.html" class="font-semibold hover:text-rara-teal transition-colors">Jadwal</a>
      <a href="members.php" class="font-semibold text-rara-teal transition-colors">Member</a>
      <a href="contact.html" class="font-semibold hover:text-rara-teal transition-colors">Kontak</a>
    </nav>
    <a href="contact.html" class="hidden lg:inline-block bg-rara-teal text-white px-6 py-2 rounded-full font-bold hover:bg-opacity-90 transition-all">
      Daftar
    </a>
    <button id="mobile-menu-btn" class="lg:hidden">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
    </button>
  </div>
  <div id="mobile-menu" class="hidden lg:hidden">
    <nav class="flex flex-col items-center gap-4 py-4">
      <a href="about.html" class="font-semibold hover:text-rara-teal transition-colors">Tentang Kami</a>
      <a href="programs.html" class="font-semibold hover:text-rara-teal transition-colors">Program</a>
      <a href="coaches.html" class="font-semibold hover:text-rara-teal transition-colors">Coach</a>
      <a href="schedule.html" class="font-semibold hover:text-rara-teal transition-colors">Jadwal</a>
      <a href="members.php" class="font-semibold text-rara-teal transition-colors">Member</a>
      <a href="contact.html" class="font-semibold hover:text-rara-teal transition-colors">Kontak</a>
      <a href="contact.html" class="bg-rara-teal text-white px-6 py-2 rounded-full font-bold hover:bg-opacity-90 transition-all mt-2">
        Daftar
      </a>
    </nav>
  </div>
</header>

<main class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Left Column: Member List -->
        <div class="lg:w-2/3">
            <h2 class="text-4xl font-heavy italic mb-8 uppercase">DAFTAR <span class="text-rara-teal">MEMBER</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6 hover:shadow-md transition-shadow">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-rara-teal flex-shrink-0">
                        <img src="assets/img/members/<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-rara-dark"><?php echo $row['name']; ?></h4>
                        <p class="text-gray-500 text-sm">Hobi: <span class="text-rara-teal font-medium"><?php echo $row['hobby']; ?></span></p>
                    </div>
                </div>
                <?php endwhile; ?>
                
                <?php if (mysqli_num_rows($result) == 0): ?>
                <div class="col-span-full py-12 text-center text-gray-400 italic">
                    Belum ada member yang terdaftar.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Registration Form -->
        <div class="lg:w-1/3">
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 sticky top-24">
                <h3 class="text-2xl font-heavy mb-6 italic">PENDAFTARAN <span class="text-rara-teal">MEMBER</span></h3>
                
                <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-xl <?php echo strpos($message, 'berhasil') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <form action="members.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-2 uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-rara-teal focus:ring-2 focus:ring-rara-teal/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 uppercase tracking-wide">Hobi</label>
                        <input type="text" name="hobby" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-rara-teal focus:ring-2 focus:ring-rara-teal/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 uppercase tracking-wide">Foto Profil</label>
                        <input type="file" name="photo" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-rara-teal/10 file:text-rara-teal hover:file:bg-rara-teal/20 cursor-pointer">
                    </div>
                    <button type="submit" name="submit" class="w-full bg-rara-teal text-white py-4 rounded-xl font-bold hover:bg-opacity-90 transition-all shadow-lg shadow-rara-teal/30 mt-4 uppercase">
                        Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>

    </div>
</main>

<footer class="bg-rara-dark text-white py-20 mt-20">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="md:col-span-2">
            <a href="index.html" class="text-2xl font-heavy mb-6 block">MEDIKAL <span class="text-rara-teal">UNDERWATER</span></a>
            <p class="text-gray-400 max-w-sm mb-8 leading-relaxed">Club Freedive yang menerapkan pembelajaran berenang yang efektif dengan pengajar yang profesional dibidang olahraga Air.</p>
        </div>
        <div>
            <h4 class="font-bold mb-6 uppercase tracking-wider">Navigasi</h4>
            <ul class="space-y-4 text-gray-400">
                <li><a href="about.html" class="hover:text-rara-teal transition-colors">Tentang Kami</a></li>
                <li><a href="programs.html" class="hover:text-rara-teal transition-colors">Program</a></li>
                <li><a href="coaches.html" class="hover:text-rara-teal transition-colors">Coach</a></li>
                <li><a href="members.php" class="hover:text-rara-teal transition-colors">Member</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-6 uppercase tracking-wider">Kontak</h4>
            <ul class="space-y-4 text-gray-400">
                <li>Depok, Indonesia</li>
                <li>+62 812 3456 7890</li>
                <li>info@medikalunderwater.com</li>
            </ul>
        </div>
    </div>
    <div class="container mx-auto px-4 mt-20 pt-8 border-t border-white/10 text-center text-gray-500 text-sm">
        <p>© 2026 Medikal Underwater. All rights reserved.</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>

</body>
</html>
