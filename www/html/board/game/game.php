<?php
require_once('../../../config/login_config.php');
require_once('../../../config/input_config.php');
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: ../board.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fish Feeding Game</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        canvas {
            display: block;
            margin: 0;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }
        .modal-content {
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 30%;
            height: 50%;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .menu-button {
            font-size: 24px;
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <canvas id="gameCanvas"></canvas>
    <div class="modal" id="menuModal">
        <div class="modal-content">
            <div class="menu-button" id="startGame">Game Start</div>
            <div class="menu-button" id="gameDescription">Game Description</div>
            <div class="menu-button" id="scoreboard">Scoreboard</div>
            <div class="menu-button" id="gameSettings">Game Settings</div>
            <div class="menu-button" id="exitGame">Exit Game</div> <!-- 나가기 버튼 추가 -->
        </div>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth; // Set canvas width to match the size of the browser window
        canvas.height = window.innerHeight; // Set canvas height to match the size of the browser window

        const fish = {
            x: canvas.width / 2,
            y: canvas.height / 2,
            size: 10, // Set the initial fish size
            maxSize: 100,
            color: 'black',
            dx: 0,
            dy: 0,
            score: 10, // Set the initial score equal to the fish's size
            eatenLargePrey: 0 // Counter for the number of times the fish ate larger prey
        };

        const circles = [];

        function drawFish() {
            ctx.beginPath();
            ctx.arc(fish.x, fish.y, fish.size, 0, Math.PI * 2);
            ctx.fillStyle = fish.color;
            ctx.fill();
            ctx.closePath();
        }

        function drawCircle(circle) {
            ctx.beginPath();
            ctx.arc(circle.x, circle.y, circle.size, 0, Math.PI * 2);
            ctx.fillStyle = circle.color;
            ctx.fill();
            ctx.closePath();
        }

        function createCircle() {
            const circle = {
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * fish.size + 5,
                color: `rgb(${Math.random() * 256}, ${Math.random() * 256}, ${Math.random() * 256})`,
                dx: Math.random() * 2 - 1, // Random horizontal speed (-1 to 1)
                dy: Math.random() * 2 - 1  // Random vertical speed (-1 to 1)
            };

            if (Math.abs(circle.x - fish.x) < 100 && Math.abs(circle.y - fish.y) < 100) {
                const speed = Math.random() * 1.5 + 0.5; // Random speed between 0.5 and 2
                if (circle.size > fish.size) {
                    circle.dx = (fish.x - circle.x) / distance * speed;
                    circle.dy = (fish.y - circle.y) / distance * speed;
                } else {
                    circle.dx = -(fish.x - circle.x) / distance * speed;
                    circle.dy = -(fish.y - circle.y) / distance * speed;
                }
            }
            
            circles.push(circle);
        }

        function checkCollision() {
            for (let i = circles.length - 1; i >= 0; i--) {
                const distance = Math.sqrt((fish.x - circles[i].x) ** 2 + (fish.y - circles[i].y) ** 2);
                if (distance < fish.size + circles[i].size) {
                    if (circles[i].size > fish.size) {
                        fish.size = fish.size / 3; // Reduce fish size to one-third if it eats something larger
                        if (fish.size < 10) {
                            fish.size = 10; // Limit the minimum fish size
                        }
                        // Change canvas color to semi-transparent red briefly when eating larger prey
                        ctx.fillStyle = "rgba(255, 0, 0, 0.3)";
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        setTimeout(() => {
                            ctx.fillStyle = "#f5f5f5";
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                        }, 200);
                        fish.eatenLargePrey++;
                        if (fish.eatenLargePrey >= 3) {
                            gameOver();
                        }
                    } else {
                        fish.size += circles[i].size * 0.05; // Increase fish size by 10% of the eaten circle's size
                    }
                    fish.score = Math.round(fish.size); // Update the score to the current fish size (rounded)
                    circles.splice(i, 1);
                } else {
                    // Move circles towards or away from the fish based on their size
                    const speed = Math.random() * 1.5 + 0.5; // Random speed between 0.5 and 2
                    if (circles[i].size > fish.size) {
                        circles[i].dx = (fish.x - circles[i].x) / distance * speed;
                        circles[i].dy = (fish.y - circles[i].y) / distance * speed;
                    } else {
                        circles[i].dx = -(fish.x - circles[i].x) / distance * speed;
                        circles[i].dy = -(fish.y - circles[i].y) / distance * speed;
                    }
                }
            }
        }

        function drawScore() {
            ctx.font = '24px Arial';
            ctx.fillStyle = 'black';
            ctx.fillText('Size : ' + Math.round(fish.size), 20, 40); // Display the fish's size (rounded) as the score
            drawHearts();
        }

        function moveFish() {
            fish.x += fish.dx;
            fish.y += fish.dy;

            if (fish.x < fish.size) {
                fish.x = fish.size;
            } else if (fish.x > canvas.width - fish.size) {
                fish.x = canvas.width - fish.size;
            }
            if (fish.y < fish.size) {
                fish.y = fish.size;
            } else if (fish.y > canvas.height - fish.size) {
                fish.y = canvas.height - fish.size;
            }
        }

        function handleKeyDown(event) {
            const key = event.keyCode;
            if (key === 37 || key === 65) { // Left arrow key or 'A' key
                fish.dx = -4;
            } else if (key === 39 || key === 68) { // Right arrow key or 'D' key
                fish.dx = 4;
            } else if (key === 38 || key === 87) { // Up arrow key or 'W' key
                fish.dy = -4;
            } else if (key === 40 || key === 83) { // Down arrow key or 'S' key
                fish.dy = 4;
            }
        }

        function handleKeyUp(event) {
            const key = event.keyCode;
            if ((key === 37 || key === 65) && fish.dx < 0 || (key === 39 || key === 68) && fish.dx > 0) {
                fish.dx = 0;
            } else if ((key === 38 || key === 87) && fish.dy < 0 || (key === 40 || key === 83) && fish.dy > 0) {
                fish.dy = 0;
            }
        }

        document.addEventListener('keydown', handleKeyDown);
        document.addEventListener('keyup', handleKeyUp);

        function showMenu() {
            const modal = document.getElementById("menuModal");
            modal.style.display = "flex";
            canvas.style.filter = "blur(4px) brightness(50%)";
        }

        function hideMenu() {
            const modal = document.getElementById("menuModal");
            modal.style.display = "none";
            canvas.style.filter = "none";
        }

        function exitGame() {
            window.location.href = '../board.php'; // '../board.php'로 이동
        }

        document.getElementById("exitGame").addEventListener("click", exitGame); // 나가기 버튼 클릭 이벤트


        document.getElementById("startGame").addEventListener("click", () => {
            hideMenu();
            resetGame();
            draw();
        });

        document.getElementById("gameDescription").addEventListener("click", () => {
            alert("Game Description: Eat the smaller circles to grow your fish. Avoid the larger circles. If you eat three larger circles, the game is over.");
        });

        document.getElementById("scoreboard").addEventListener("click", () => {
            alert('Work in progress...')
        });

        document.getElementById("gameSettings").addEventListener("click", () => {
            alert("Work in progress...");
        });

        setInterval(createCircle,500); // Spawn circles every 1 second (more active)

        function drawHearts() {
            const heartSize = 30;
            const padding = 10;
            for (let i = 0; i < 3 - fish.eatenLargePrey; i++) {
                ctx.font = "30px Arial";
                ctx.fillStyle = "red";
                ctx.fillText("❤", canvas.width - (i + 1) * (heartSize + padding), padding + heartSize);
            }
        }

        function gameOver() {
            <?php
            // 점수 저장하기
            if (isset($_SESSION['score'])) {
                $score = $_SESSION['score'];
                $userid = $_SESSION['userid'];

                // 이전 최고 점수 가져오기
                $sql = "SELECT highest_score FROM game WHERE login_id = '$userid'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $highest_score = $row['highest_score'];

                // 최고 점수 갱신 확인
                if ($score > $highest_score) {
                    $sql = "UPDATE game SET highest_score = '$score' WHERE login_id = '$userid'";
                    mysqli_query($conn, $sql);
                }

                // 점수를 세션에서 삭제
                unset($_SESSION['score']);
            }
            ?>
            
            alert("Game Over! Your Final Score: " + fish.eatenLargePrey);
            resetGame();
            showMenu();
        }

        function resetGame() {
            fish.size = 10;
            fish.score = 10;
            fish.eatenLargePrey = 0;
            circles.length = 0;
        }

        function draw() {
            ctx.fillStyle = "#f5f5f5"; // Set the background color to light gray
            ctx.fillRect(0, 0, canvas.width, canvas.height); // Draw the background

            moveFish();

            drawFish();

            for (let i = 0; i < circles.length; i++) {
                drawCircle(circles[i]);

                circles[i].x += circles[i].dx;
                circles[i].y += circles[i].dy;

                // Wrap the circles around the canvas edges
                if (circles[i].x < -circles[i].size) {
                    circles[i].x = canvas.width + circles[i].size;
                } else if (circles[i].x > canvas.width + circles[i].size) {
                    circles[i].x = -circles[i].size;
                }
                if (circles[i].y < -circles[i].size) {
                    circles[i].y = canvas.height + circles[i].size;
                } else if (circles[i].y > canvas.height + circles[i].size) {
                    circles[i].y = -circles[i].size;
                }
            }

            drawScore();

            if (fish.size >= fish.maxSize) {
                fish.size = 100; // Reset fish size after reaching the maximum
                fish.score = 10; // Reset the score to the initial size
                fish.eatenLargePrey = 0;
            }

            checkCollision();

            requestAnimationFrame(draw);
        }

        showMenu();
    </script>
</body>
</html>
