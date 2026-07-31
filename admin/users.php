<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*update role*/
if(isset($_POST['update_role'])){

    $user_id = (int)($_POST['user_id'] ?? 0);

    $allowed_roles = [
        "user",
        "admin",
        "technician"
    ];

    $role = $_POST['role'] ?? "";

    if(!in_array($role, $allowed_roles, true)){

        $_SESSION['role_error'] = "Invalid user role selected.";

    }else{

        $role_db = mysqli_real_escape_string($conn, $role);

        $update_role = mysqli_query(
            $conn,
            "UPDATE users
             SET role='$role_db'
             WHERE user_id='$user_id'"
        );

        if($update_role){

            $_SESSION['role_success'] = "User role updated successfully.";

        }else{

            $_SESSION['role_error'] = "Unable to update the user role.";
        }
    }

    header("Location: users.php");
    exit();
}

/*delete user*/
if(isset($_GET['delete'])){

    $delete_id = (int)$_GET['delete'];

    /*prevent admin from deleting own account*/
    if($delete_id === (int)$_SESSION['user_id']){

        $_SESSION['delete_user_error'] = "You cannot delete your own administrator account.";

        header("Location: users.php");
        exit();
    }

    $delete_user = mysqli_query(
        $conn,
        "DELETE FROM users
         WHERE user_id='$delete_id'"
    );

    if($delete_user && mysqli_affected_rows($conn) > 0){

        $_SESSION['delete_user_success'] = "User deleted successfully.";

    }else{

        $_SESSION['delete_user_error'] = "Unable to delete the user. The account may no longer exist.";
    }

    header("Location: users.php");
    exit();
}

$users = mysqli_query(
    $conn,
    "SELECT * FROM users 
     ORDER BY user_id DESC"
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Users - ReTech Hub</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/admin-users.css">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="admin-layout">
            
            <!--side bar-->
            <aside class="sidebar">
                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="users.php" class="active"><i class="fa-solid fa-users"></i> Users</a>
                <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
                <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
                <a href="repairs.php"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
                <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i>Messages</a>
                <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </aside>

            <main class="admin-main">
                
                <!--top bar-->
                <div class="admin-top">
                    <form class="admin-search" action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Search user ID, product, booking ID, order ID..."required>

                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <div class="admin-icons">
                        <a href="messages.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-message"></i><span>Message</span>
                            <span id="messageBadge" class="live-badge" style="display:none;"> 0</span></a>

                        <a href="notifications.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-bell"></i><span>Notification</span>

                            <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span></a>

                        <a href="../profile.php" class="admin-profile">
                            <img
                            src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>"
                            class="admin-avatar">

                            <span><?= $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </div>

                <!--page code-->
                <div class="users-header">
                    <div>
                        <h1>User Management</h1>
                        <p>View, manage and update user roles.</p>
                    </div>
                </div>

                <!--user table-->
                <div class="users-table-card">

                    <table>
                        <tr>
                            <th>Profile</th>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>

                        <?php if(mysqli_num_rows($users) > 0){ ?>

                            <?php while($u = mysqli_fetch_assoc($users)){ ?>

                                <tr>
                                    <td>
                                        <img src="../<?= $u['profile_image'] ?? 'uploads/profile/default.png'; ?>" class="user-thumb">
                                    </td>
                                    <td>#<?= $u['user_id']; ?></td>
                                    <td><?= $u['username']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['phone']; ?></td>
                                    <td>
                                        <form method="POST" class="role-form">

                                            <input type="hidden" name="user_id" value="<?= $u['user_id']; ?>">

                                            <select name="role">
                                                <option value="user" <?= $u['role']=='user'?'selected':''; ?>>User</option>
                                                <option value="admin" <?= $u['role']=='admin'?'selected':''; ?>>Admin</option>
                                                <option value="technician" <?= $u['role']=='technician'?'selected':''; ?>>Technician</option>
                                            </select>

                                            <!--update role button-->
                                            <button type="submit" name="update_role"> Save </button>

                                        </form>
                                    </td>

                                    <td><?= $u['created_at']; ?></td>

                                    <td>
                                        <?php if($u['user_id'] != $_SESSION['user_id']){ ?>

                                            <!--delete button-->
                                            <a href="#"
                                                class="delete-user-btn"
                                                onclick="confirmDeleteUser(
                                                    <?= (int)$u['user_id']; ?>,
                                                    <?= htmlspecialchars(
                                                        json_encode($u['username']),
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ); ?>
                                                ); return false;">

                                                Delete
                                            </a>

                                        <?php }else{ ?>

                                            <span class="self-label">
                                                Current Admin
                                            </span>

                                        <?php } ?>
                                    </td>
                                </tr>

                            <?php } ?>

                        <?php }else{ ?>

                            <!--display if no user-->
                            <tr>
                                <td colspan="8" class="empty-row">
                                    No users found.
                                </td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Delete confirmation -->
        <script>
        function confirmDeleteUser(userId, username) {

            Swal.fire({
                icon: "warning",
                title: "Delete User?",
                text: "Are you sure you want to delete " +
                    username +
                    "? This action cannot be undo.",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#e53935",
                cancelButtonColor: "#6c757d",

            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href =
                        "users.php?delete=" +
                        encodeURIComponent(userId);
                }

            });
        }
        </script>

        <!-- Delete success window -->
        <?php if(isset($_SESSION['delete_user_success'])){ ?>

        <script>
        Swal.fire({
            icon: "success",
            title: "User Deleted",
            text: <?= json_encode($_SESSION['delete_user_success']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['delete_user_success']);
        }
        ?>

        <!-- Delete error window -->
        <?php if(isset($_SESSION['delete_user_error'])){ ?>

        <script>
        Swal.fire({
            icon: "error",
            title: "Unable to Delete User",
            text: <?= json_encode($_SESSION['delete_user_error']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['delete_user_error']);
        }
        ?>

        <!-- Role update success window -->
        <?php if(isset($_SESSION['role_success'])){ ?>

        <script>
        Swal.fire({
            icon: "success",
            title: "Role Updated",
            text: <?= json_encode($_SESSION['role_success']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['role_success']);
        }
        ?>

        <!-- Role update error window -->
        <?php if(isset($_SESSION['role_error'])){ ?>

        <script>
        Swal.fire({
            icon: "error",
            title: "Unable to Update Role",
            text: <?= json_encode($_SESSION['role_error']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['role_error']);
        }
        ?>

    </body>
</html>