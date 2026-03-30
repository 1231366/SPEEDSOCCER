<?php
// fazer_reserva.php

// 1. IMPORTAÇÃO DAS CLASSES (Obrigatório no topo)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 2. CARREGAMENTO MANUAL (Ajusta o caminho se a tua pasta tiver outro nome)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once 'config/db.php';

// Ativar relatório de erros para testes (remover em produção)
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
        // 1. Verificar disponibilidade
        $check = $pdo->prepare("SELECT id FROM reservas WHERE campo_id = ? AND data_jogo = ? AND hora_inicio = ? AND status != 'cancelado'");
        $check->execute([$campo_id, $data, $hora]);

        if ($check->rowCount() > 0) {
            header("Location: index.php?status=erro_ocupado#reservar");
            exit;
        }

        // 2. Inserir na BD
        $sql = "INSERT INTO reservas (campo_id, nome_cliente, email, telemovel, data_jogo, hora_inicio, hora_fim, valor_total) VALUES (?, ?, ?, ?, ?, ?, ?, 65.00)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$campo_id, $nome, $email, $telemovel, $data, $hora, $hora_fim]);
        $reserva_id = $pdo->lastInsertId();

        // 3. Configurar PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tiagofsilva04@gmail.com'; // O teu e-mail
        $mail->Password   = 'yrzs irbp duou pxfz'; // A tua App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('tiagofsilva04@gmail.com', 'Speed Soccer Gaia');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);
        $mail->Subject = "⚽ Jogo Confirmado: $nome_campo - " . date('d/m', strtotime($data));

        // Links e Dados Dinâmicos
        $cancel_token = md5($reserva_id . "secret");
        $cancel_link = "http://localhost/speedsoccer/cancelar.php?id=$reserva_id&token=$cancel_token";
        
        $data_agenda_start = date('Ymd\THis', strtotime("$data $hora"));
        $data_agenda_end = date('Ymd\THis', strtotime("$data $hora_fim"));
        $morada_completa = "Rua dos Campos Alegres 237, 4415-722 Olival, Vila Nova de Gaia";
        $google_cal = "https://www.google.com/calendar/render?action=TEMPLATE&text=Jogo+Speed+Soccer&dates=$data_agenda_start/$data_agenda_end&details=Reserva+no+$nome_campo&location=" . urlencode($morada_completa);

        // Imagem estática do mapa (substitui por uma imagem real do teu mapa se preferires)
        $mapa_img = "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($morada_completa) . "&zoom=15&size=600x300&maptype=roadmap&markers=color:red%7C" . urlencode($morada_completa) . "&key=A tua_Google_Maps_API_Key";
        // NOTA: Para usar o mapa estático do Google, precisas de uma API Key. Se não tiveres, usei uma alternativa genérica abaixo.

        // CORPO DO E-MAIL (SUPER PREMIUM DARK MODE)
        $mail->Body = "
        <!DOCTYPE html>
        <html lang='pt'>
        <head>
            <meta charset='UTF-8'>
            <title>Confirmação de Reserva</title>
        </head>
        <body style='margin: 0; padding: 0; background-color: #000000; color: #ffffff; font-family: \"Space Grotesk\", sans-serif;'>
            <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #000000;'>
                <tr>
                    <td align='center' style='padding: 40px 10px;'>
                        <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%' style='max-w: 600px; background-color: #0e0e0e; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);'>
                            <tr>
                                <td align='center' style='padding: 30px; border-bottom: 1px solid rgba(142,255,113,0.1);'>
                                    <h1 style='margin: 0; color: #8eff71; font-style: italic; font-weight: 900; font-size: 32px; tracking-tighter; text-transform: uppercase;'>SPEED SOCCER</h1>
                                </td>
                            </td>
                            <tr>
                                <td style='padding: 30px; text-align: center;'>
                                    <p style='margin: 0 0 20px; font-size: 18px; line-height: 1.5;'>Olá <strong>$nome</strong>, o teu jogo no <span style='color: #8eff71; font-weight: bold;'>Speed Soccer Gaia</span> está confirmado!</p>
                                    
                                    <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #1a1919; border-radius: 15px; border: 1px solid #8eff71; margin-bottom: 20px;'>
                                        <tr>
                                            <td style='padding: 20px; text-align: center;'>
                                                <h2 style='margin: 0 0 10px; color: #8eff71; font-size: 24px; text-transform: uppercase;'>$nome_campo</h2>
                                                <p style='margin: 0; font-size: 18px; font-weight: bold;'>📅 " . date('d/m/Y', strtotime($data)) . " | ⏰ $hora - " . date('H:i', strtotime($hora_fim)) . "</p>
                                                <p style='margin: 10px 0 0; font-size: 12px; color: #888;'>Preço: €65.00 (pagamento no local)</p>
                                            </td>
                                        </tr>
                                    </table>

                                    <a href='$google_cal' target='_blank' style='display: inline-block; background-color: #8eff71; color: #000; padding: 18px 30px; text-decoration: none; font-weight: bold; font-size: 16px; border-radius: 12px; text-transform: uppercase; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(142,255,113,0.3);'>ADICIONAR À AGENDA 📅</a>
                                </td>
                            </td>
                            <tr>
                                <td style='background-color: #131313; border-top: 1px solid rgba(255,255,255,0.05); border-radius: 0 0 20px 20px;'>
                                    <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                        <tr>
                                            <td style='padding: 20px;'>
                                                <p style='margin: 0 0 10px; color: #888; font-size: 10px; text-transform: uppercase; tracking-widest;'>Localização</p>
                                                <p style='margin: 0; font-size: 14px; font-weight: bold; color: #ffffff; line-height: 1.4;'>Rua dos Campos Alegres 237,<br>4415-722 Olival</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 0 20px 20px;'>
                                                <a href='https://www.google.com/maps/search/?api=1&query=" . urlencode($morada_completa) . "' target='_blank'>
                                                    <img src='https://static-maps.yandex.ru/1.x/?lang=pt_PT&ll=-8.5600,41.0600&z=15&l=map&size=560,250&pt=-8.5600,41.0600,pm2rdm' width='100%' style='border-radius: 10px; display: block;' alt='Mapa de localização'>
                                                </a>
                                                </td>
                                        </tr>
                                    </table>
                                </td>
                            </td>
                            <tr>
                                <td style='padding: 30px; text-align: center; font-size: 12px; color: #666;'>
                                    <p style='margin: 0;'>Precisas de ajuda? Contacta-nos: 912 345 678</p>
                                    <p style='margin: 10px 0;'><a href='$cancel_link' style='color: #ff7351; text-decoration: underline;'>Cancelar Reserva</a></p>
                                    <p style='margin: 20px 0 0;'>© 2024 Speed Soccer Gaia - Tecnologia e Performance.</p>
                                </td>
                            </td>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

        $mail->send();
        header("Location: index.php?status=sucesso#reservar");

    } catch (Exception $e) {
        // Se falhar o envio mas gravou na BD, ainda mostramos sucesso mas avisamos internamente
        header("Location: index.php?status=sucesso_sem_email#reservar");
    }
}