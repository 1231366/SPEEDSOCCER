<?php
// fazer_reserva.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once 'config/db.php'; // Já carrega o env_loader.php internamente

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telemovel = filter_input(INPUT_POST, 'telemovel', FILTER_SANITIZE_SPECIAL_CHARS);
    $data = $_POST['data_jogo'];
    $hora = $_POST['hora_inicio'];
    $campo_id = (int)$_POST['campo_id'];
    $nome_campo = ($campo_id == 1) ? "Campo Speed 1" : "Campo Speed 2";
    
    $hora_fim = date('H:i:s', strtotime("$hora +1 hour"));

    try {
        $check = $pdo->prepare("SELECT id FROM reservas WHERE campo_id = ? AND data_jogo = ? AND hora_inicio = ? AND status != 'cancelado'");
        $check->execute([$campo_id, $data, $hora]);

        if ($check->rowCount() > 0) {
            header("Location: index.php?status=erro_ocupado#reservar");
            exit;
        }

        $sql = "INSERT INTO reservas (campo_id, nome_cliente, email, telemovel, data_jogo, hora_inicio, hora_fim, valor_total) VALUES (?, ?, ?, ?, ?, ?, ?, 65.00)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$campo_id, $nome, $email, $telemovel, $data, $hora, $hora_fim]);
        $reserva_id = $pdo->lastInsertId();

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS']; // Protegido via .env
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($_ENV['SMTP_USER'], 'Speed Soccer Gaia');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);
        $mail->Subject = "⚽ Jogo Confirmado: $nome_campo - " . date('d/m', strtotime($data));

        // Token gerado com segredo do .env para máxima segurança
        $cancel_token = md5($reserva_id . $_ENV['APP_SECRET']);
        $cancel_link = "http://localhost/speedsoccer/cancelar.php?id=$reserva_id&token=$cancel_token";
        
        $data_agenda_start = date('Ymd\THis', strtotime("$data $hora"));
        $data_agenda_end = date('Ymd\THis', strtotime("$data $hora_fim"));
        $morada_completa = "Rua dos Campos Alegres 237, 4415-722 Olival, Vila Nova de Gaia";
        $google_cal = "https://www.google.com/calendar/render?action=TEMPLATE&text=Jogo+Speed+Soccer&dates=$data_agenda_start/$data_agenda_end&details=Reserva+no+$nome_campo&location=" . urlencode($morada_completa);

        $mail->Body = "
        <!DOCTYPE html>
        <html lang='pt'>
        <head>
            <meta charset='UTF-8'>
            <title>Confirmação de Reserva</title>
        </head>
        <body style='margin: 0; padding: 0; background-color: #000000; color: #ffffff; font-family: sans-serif;'>
            <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #000000;'>
                <tr>
                    <td align='center' style='padding: 40px 10px;'>
                        <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px; background-color: #0e0e0e; border-radius: 20px;'>
                            <tr>
                                <td align='center' style='padding: 30px; border-bottom: 1px solid #8eff71;'>
                                    <h1 style='margin: 0; color: #8eff71; text-transform: uppercase;'>SPEED SOCCER</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 30px; text-align: center;'>
                                    <p>Olá <strong>$nome</strong>, o teu jogo está confirmado!</p>
                                    <h2 style='color: #8eff71;'>$nome_campo</h2>
                                    <p>📅 " . date('d/m/Y', strtotime($data)) . " | ⏰ $hora</p>
                                    <a href='$google_cal' style='background: #8eff71; color: #000; padding: 15px 25px; text-decoration: none; border-radius: 10px;'>ADICIONAR À AGENDA</a>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 30px; text-align: center;'>
                                    <p><a href='$cancel_link' style='color: #ff7351;'>Cancelar Reserva</a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

        $mail->send();
        header("Location: index.php?status=sucesso#reservar");

    } catch (Exception $e) {
        header("Location: index.php?status=sucesso_sem_email#reservar");
    }
}