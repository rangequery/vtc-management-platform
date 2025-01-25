<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramBot
{

    /**
     * Done! Congratulations on your new bot. You will find it at t.me/fxbysignalBot. You can now add a description, about section and profile picture for your bot, see /help for a list of commands. By the way, when you've finished creating your cool bot, ping our Bot Support if you want a better username for it. Just make sure the bot is fully operational before you do this.
     *
     * Use this token to access the HTTP API:
     * 7261515339:AAFfN-2e0plw_RxLBadYrW68Lh1MncV65Gk
     * Keep your token secure and store it safely, it can be used by anyone to control your bot.
     *
     * For a description of the Bot API, see this page: https://core.telegram.org/bots/api
     */

    public function sendMessage($message, $updatedMessage = null): bool|string
    {
        $current_url = "$_SERVER[HTTP_HOST]";
        // Chat id pour Test
        $chatId = "-1002235690936"; // Dev-Sayn
        if (strpos($current_url, 'fxbysignal.com') !== false) {
            // Chat id pour la prod #FXBYSIGNAL
            $chatId = "-1002191794109 OFFFFFFFF";
        }

        // Remplacez par votre token de bot
        $botToken = "7261515339:AAFfN-2e0plw_RxLBadYrW68Lh1MncV65Gk";

        // L'URL de l'API Telegram pour envoyer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

        // Utilisation de curl pour envoyer une requête POST pour envoyer le message
        $ch = curl_init();

        // Les paramètres à envoyer
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        // Placeholder pour l'ID du message
                        ['text' => 'CONFIRMER LA COURSE ?', 'url' => 'https://127.0.0.1:8001/confirmation?message_id={message_id}']  // Lien vers lequel le bouton redirige
                    ]
                ]
            ], JSON_THROW_ON_ERROR)
        ];

        // Initialiser cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécutez la requête et obtenez la réponse
        $response = curl_exec($ch);

        // Fermez la session curl
        curl_close($ch);

        // Convertir la réponse JSON en tableau PHP
        $responseData = json_decode($response, true);

        // Vérifier si l'envoi du message a réussi et récupérer l'ID du message
        if (isset($responseData['result']['message_id'])) {
            $messageId = $responseData['result']['message_id'];

            // Remplacer {message_id} par l'ID du message dans l'URL
            $confirmationUrl = "https://127.0.0.1:8001/confirmation/" . $messageId;

            // Mettre à jour la variable reply_markup avec le lien contenant l'ID du message
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '⚠️ CONFIRMER LA COURSE ❓', 'url' => $confirmationUrl]
                    ]
                ]
            ], JSON_THROW_ON_ERROR);

            // Réinitialiser cURL pour modifier le message
            $ch = curl_init();
            $urlEdit = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

            // Préparer les données pour l'édition du message
            $editData = [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'reply_markup' => $data['reply_markup'], // Utilise la nouvelle reply_markup avec l'ID du message

            ];

            // Configurer cURL pour l'édition du message
            curl_setopt($ch, CURLOPT_URL, $urlEdit);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($editData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Exécutez la requête d'édition et obtenez la réponse
            $editResponse = curl_exec($ch);
            curl_close($ch);

            // Optionnel : afficher la réponse d'édition si nécessaire
            return $editResponse;
        }

        // Si l'envoi du message échoue, retourner la réponse d'erreur
        return $response;
    }






    public function updateMessageConfirmation($messageId, $updatedMessage): bool|string
    {
        $current_url = "$_SERVER[HTTP_HOST]";
        // Chat id pour Test
        $chatId = "-1002235690936"; // Dev-Sayn
        if (strpos($current_url, 'fxbysignal.com') !== false) {
            // Chat id pour la prod #FXBYSIGNAL
            $chatId = "-1002191794109 OFFFFFFFF";
        }

        // Remplacez par votre token de bot
        $botToken = "7261515339:AAFfN-2e0plw_RxLBadYrW68Lh1MncV65Gk";

        // L'URL de l'API Telegram pour éditer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

        $finishedUrl = "https://127.0.0.1:8001/finished/" . $messageId;

        // Paramètres à envoyer
        $data = [
            'chat_id' => $chatId,  // Assurez-vous que le chat_id est correctement défini
            'message_id' => $messageId,
            'text' => $updatedMessage,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '✅ COURSE TERMINER ❓', 'url' => $finishedUrl] // Lien vers lequel le bouton redirige
                    ]
                ]
            ], JSON_THROW_ON_ERROR)
        ];
        // Utilisation de curl pour envoyer une requête POST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécutez la requête et obtenez la réponse
        $response = curl_exec($ch);

        // Fermez la session curl
        curl_close($ch);

        // Retournez la réponse
        return $response;
    }

    public function updateMessageFinished($messageId, $updatedMessage): bool|string
    {
        $current_url = "$_SERVER[HTTP_HOST]";
        // Chat id pour Test
        $chatId = "-1002235690936"; // Dev-Sayn
        if (strpos($current_url, 'fxbysignal.com') !== false) {
            // Chat id pour la prod #FXBYSIGNAL
            $chatId = "-1002191794109 OFFFFFFFF";
        }

        // Remplacez par votre token de bot
        $botToken = "7261515339:AAFfN-2e0plw_RxLBadYrW68Lh1MncV65Gk";

        // L'URL de l'API Telegram pour éditer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/editMessageText";


        // Paramètres à envoyer
        $data = [
            'chat_id' => $chatId,  // Assurez-vous que le chat_id est correctement défini
            'message_id' => $messageId,
            'text' => $updatedMessage,
            'parse_mode' => 'Markdown',
        ];
        // Utilisation de curl pour envoyer une requête POST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécutez la requête et obtenez la réponse
        $response = curl_exec($ch);

        // Fermez la session curl
        curl_close($ch);

        // Retournez la réponse
        return $response;
    }

}