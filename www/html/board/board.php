<?php
session_start();
?>

<!doctype html>

<head>
    <meta charset="UTF-8">
    <title>KO Forum</title>
    <link rel="stylesheet" href="../style/style_board.css">
</head>

<body>
    <div id="nav">
        <?php
        if (isset($_SESSION['userid'])) {
            echo '<a href="../login/logout.php">Logout :(</a>';
            echo '<a id="hello-name">ID : ' . $_SESSION['userid'] . '</a>';
        } else {
            echo '<a href="../login/login.php">Login :)</a>';
        }
        ?>
    </div>
    <div id="board_area">
        <h1>Forum</h1>
        <div id="search_box">
            <form action="board_searched.php" method="get">
                <select name="catgo">
                    <option value="title">title</option>
                    <option value="name">name</option>
                    <option value="content">content</option>
                </select>
                <input type="text" name="search" size="40" required="required" /> <button>Search</button>
            </form>
        </div>

        <?php
        require_once ('../../config/login_config.php');
        require_once ('../../config/input_config.php');

        if (!isset($conn)) {
            echo "<script>alert('DB 컨테이너 연결 대기 중..!'); window.location.href = '/index.php';</script>";
            exit;
        }

        $sql = mysqli_query($conn, "ALTER TABLE board AUTO_INCREMENT=1");
        $sql = mysqli_query($conn, "SET @COUNT = 0");
        $sql = mysqli_query($conn, "UPDATE board SET board.idx = @COUNT:=@COUNT+1");

        $page = isset($_GET['page']) ? (int) sqli_checker($conn, $_GET['page']) : 1;
        if (isset($page)) {
            if ($page < 1) {
                $page = 1;
            }

            $sql = mysqli_query($conn, "SELECT COUNT(*) AS total FROM board");
            $result = mysqli_fetch_assoc($sql);
            $total_page = ceil($result['total'] / 10);
            if ($page > $total_page) {
                $page = $total_page;
            }
        } else {
            $page = 1;
        }

        $sql = mysqli_query($conn, "select idx from board");
        $row_num = mysqli_num_rows($sql);
        $list = 10;
        $block_ct = 5;

        $block_num = ceil($page / $block_ct);
        $block_start = (($block_num - 1) * $block_ct) + 1;
        $block_end = $block_start + $block_ct - 1;


        if ($block_start < 0) {
            $block_start = 0;
        }

        $total_page = ceil($row_num / $list);
        if ($block_end > $total_page)
            $block_end = $total_page;
        $total_block = ceil($total_page / $block_ct);
        $start_num = ($page - 1) * $list;

        $sql2 = mysqli_query($conn, "select * from board order by idx desc limit $start_num, $list");

        ?>

        <table class="list-table">
            <thead>
                <tr>
                    <th width="70">No.</th>
                    <th width="500">Title</th>
                    <th width="120">Poster</th>
                    <th width="200">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sql2 === false) {
                    echo "<tr><td colspan='4'>No data :(</td></tr>";
                } else {
                    while ($board = $sql2->fetch_array()) {
                        $title = $board["title"];
                        $max_length = 30;
                        if (strlen($title) > $max_length) {
                            $title = substr($title, 0, $max_length) . "...";
                        }
                        ?>
                        <tr>
                            <td width="70">
                                <?php echo xss_checker($board['idx']); ?>
                            </td>
                            <td width="500"><a href="read.php?idx=<?php echo xss_checker($board["idx"]); ?>">
                                    <?php echo xss_checker($title); ?>
                                </a><span id="hit">
                                    <?php echo $board['hit']; ?>
                                </span></td>
                            <td width="120">
                                <?php echo xss_checker($board['name']) ?>
                            </td>
                            <td width="100">
                                <?php echo xss_checker($board['date']) ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <hr>
        <div id="page_num">
            <ul>
                <?php
                if ($page <= 5) {
                    echo "<li><a class='paging-bt'>Prev</a></li>";
                } else {
                    $pre = $page - 5;
                    echo "<li><a class='paging-bt' href='?page=$pre'>Prev</a></li>";
                }
                for ($i = $block_start; $i <= $block_end; $i++) {
                    if ($page == $i) {
                        echo "<li><a class='paging-bt current-page'>$i</a></li>";
                    } else {
                        echo "<li><a class='paging-bt' href='?page=$i'>$i</a></li>";
                    }
                }
                if ($block_num >= $total_block) {
                    echo "<li><a class='paging-bt'>Next</a></li>";
                } else {
                    $next = $page + 5;
                    echo "<li><a class='paging-bt' href='?page=$next'>Next</a></li>";
                }
                ?>
            </ul>
        </div>
        <div id="write_btn">
            <a href="write.php"><button>Post!</button></a>
        </div>
    </div>
</body>

</html>