<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'police_vehicles');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $plate = $_POST['plate'];
        $owner = $_POST['owner'];
        $type = $_POST['type'];
        $stmt = $conn->prepare("INSERT INTO vehicles (plate, owner, type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $plate, $owner, $type);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $conn->query("DELETE FROM vehicles WHERE id = $id");
    } elseif (isset($_POST['reset'])) {
        // Reset tabel dan ID
        $conn->query("TRUNCATE TABLE vehicles");
    }
}

$result = $conn->query("SELECT * FROM vehicles");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            color: #333;
        }

        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 800px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .add-button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            background:rgb(151, 17, 166);
            color: #fff;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-button:hover {
            background:rgb(212, 17, 219);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background:rgb(162, 19, 209);
            color: #fff;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background: #ff6f61;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        a:hover {
            background: #ff4a45;
        }

        .reset-button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            background: #ff4a45;
            color: #fff;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .reset-button:hover {
            background: #cc372f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CATATAN KEUANGAN</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Saldo</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['plate']; ?></td>
                <td><?php echo $row['owner']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <form method="POST">
            <div class="form-row">
                <input type="text" name="plate" placeholder="Isi nama" required>
                <input type="text" name="owner" placeholder="Isi saldo" required>
                <input type="text" name="type" placeholder="Tanggal" required>
            </div>
            <button class="add-button" type="submit" name="add">Tambah</button>
        </form>
        <form method="POST" style="margin-top: 20px;">
            <button class="reset-button" type="submit" name="reset">Reset ID</button>
        </form>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
