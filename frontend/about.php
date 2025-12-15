<?php

session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="style.css">
    <style>
       
        .about-container {
            max-width: 900px;
            margin: 100px auto 50px;
            padding: 30px;
            background-color: #1E1E1E;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .about-container h2 {
            color: #FFD700;
            font-size: 32px;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .about-container p {
            color: #ccc;
            margin-bottom: 40px;
            line-height: 1.8;
            font-size: 16px;
        }
        .team-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 42px;
            margin-top: 30px;
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
        }

        .team-member {
            width: 200px;
            background-color: #282828;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        .member-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #00BFFF;
            display: block;
            margin: 0 auto 10px;
        }
        .team-member h4 {
            color: white;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .team-member p {
            color: #aaa;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <?php include 'backend/header.php'; ?>
    
    <div class="about-container">
        
        <h2>Mengenai AboutJo Forum</h2>
        <p>
            AboutJo dibuat sebagai platform komunitas bagi para penggemar Jojo's Bizarre Adventure. 
            Tujuan kami adalah menyediakan ruang bagi komunitas dan pengguna untuk berbagi pengetahuan, 
            diskusi mendalam, dan Membangun komunitas yang sehat. Dengan fitur fitur yang ada di web ini kami berharap bisa mencapai tujuan tersebut 
        </p>
        <div style="margin: 40px 0;">
       
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 40px;">
            
            <div style="background: rgba(40, 40, 40, 0.5); padding: 20px; border-radius: 8px; text-align: center;">
                <h4 style="color: #FFD700; margin-bottom: 10px;">Mini Game Arena</h4>
                <p style="color: #ccc; font-size: 13px;">
                    Uji kemampuan refleksmu melawan musuh yang terus berdatangan. 
                    Bersaing di leaderboard dengan pemain lain!
                </p>
            </div>
            
            <div style="background: rgba(40, 40, 40, 0.5); padding: 20px; border-radius: 8px; text-align: center;">
                
                <h4 style="color: #FFD700; margin-bottom: 10px;">Community Feed</h4>
                <p style="color: #ccc; font-size: 13px;">
                    Bagikan pendapat, teori, dan diskusi dengan sesama penggemar JoJo 
                    dalam sistem posting interaktif.
                </p>
            </div>
            
            <div style="background: rgba(40, 40, 40, 0.5); padding: 20px; border-radius: 8px; text-align: center;">
                
                <h4 style="color: #FFD700; margin-bottom: 10px;">Leaderboard System</h4>
                <p style="color: #ccc; font-size: 13px;">
                    Sistem ranking yang mencatat pencapaian terbaik setiap pemain 
                    dalam mini game arena.
                </p>
            </div>
            
            <div style="background: rgba(40, 40, 40, 0.5); padding: 20px; border-radius: 8px; text-align: center;">
               
                <h4 style="color: #FFD700; margin-bottom: 10px;">User Authentication</h4>
                <p style="color: #ccc; font-size: 13px;">
                    Sistem login yang aman untuk menyimpan progres game 
                    dan aktivitas komunitasmu.
                </p>
            </div>

            
            
        </div>
    </div>
    

    
            </div>

        </div>
    </div>
    
</body>
</html>