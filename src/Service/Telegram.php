<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Telegram
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
    private string $urlApp; // Déclaration de la propriété pour l'URL
    private string $current_url; // Déclaration de la propriété pour l'URL

    public function __construct()
    {
        $current_url = "$_SERVER[HTTP_HOST]";
        if (strpos($current_url, 'pickup.codeix.io') !== false) {
            // Chat id pour la prod #FXBYSIGNAL
            $this->urlApp = "pickup.codeix.io";
        } else {
            $this->urlApp = "127.0.0.1:8000";
        }

        $this->current_url = $current_url;
    }

    public function sendMessage($message, $chatID): bool|string
    {

        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // Les paramètres à envoyer
        $data = [
            'chat_id' => $chatID,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        // Placeholder pour l'ID du message
                        ['text' => '⚠️ CONFIRMER LA COURSE ❓', 'callback_data' => 'confirm_course']
                    ]
                ]
            ], JSON_THROW_ON_ERROR)
        ];

        // Utilisation de curl pour envoyer une requête POST pour envoyer le message
        $ch = curl_init();
        // L'URL de l'API Telegram pour envoyer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

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
            $confirmationUrl =  $this->urlApp ."/private/service/confirmation/" . $messageId;

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
                'chat_id' => $chatID,
                'message_id' => $messageId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
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

    public function updateMessageConfirmation($messageId, $chatID, $updatedMessage): bool|string
    {

        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // L'URL de l'API Telegram pour éditer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

        $departdUrl = $this->urlApp ."/private/service/depart/" . $messageId;

        // Paramètres à envoyer
        $data = [
            'chat_id' => $chatID,  // Assurez-vous que le chat_id est correctement défini
            'message_id' => $messageId,
            'text' => $updatedMessage,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'DEPART ✅', 'url' => $departdUrl] // Lien vers lequel le bouton redirige
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

    public function updateMessageDepart($messageId, $chatID, $updatedMessage): bool|string
    {

        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // L'URL de l'API Telegram pour éditer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

        $finishedUrl = $this->urlApp ."/private/service/finished/" . $messageId;

        // Paramètres à envoyer
        $data = [
            'chat_id' => $chatID,  // Assurez-vous que le chat_id est correctement défini
            'message_id' => $messageId,
            'text' => $updatedMessage,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '🏁 TERMINER ?', 'url' => $finishedUrl] // Lien vers lequel le bouton redirige
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

    public function updateMessageFinished($messageId, $chatID, $updatedMessage): bool|string
    {
        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // L'URL de l'API Telegram pour éditer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

        // Paramètres à envoyer
        $data = [
            'chat_id' => $chatID,  // Assurez-vous que le chat_id est correctement défini
            'message_id' => $messageId,
            'text' => $updatedMessage,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
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

    public function deleteTelegramMessage($chatId, $messageId)
    {
        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.
        // URL de l'API Telegram pour supprimer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/deleteMessage";

        // Données pour la requête POST
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ];

        // Initialiser cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécuter la requête
        $response = curl_exec($ch);

        // Fermer cURL
        curl_close($ch);

        // Retourner la réponse
        return $response;
    }

    public function sendMessageV2($message, $chatID, $route): bool|string
    {
        $current_url = "$_SERVER[HTTP_HOST]";

        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // Utilisation de curl pour envoyer une requête POST pour envoyer le message
        $ch = curl_init();

        switch ($route) {
            case "envoi":
                $text = "⚠️ CONFIRMER LA COURSE ❓";
                break;

            case "confirmation":
                $text = "✅ COURSE TERMINER ❓";
                break;

            default:
                $text = "";
                break;
        }

        // Manager
        if ($chatID === "-4509876234") {
            // Les paramètres à envoyer
            $data = [
                'chat_id' => $chatID,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
            ];
        } else { // Chauffeur
            // Les paramètres à envoyer
            $data = [
                'chat_id' => $chatID,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            // Placeholder pour l'ID du message
                            ['text' => $text, 'callback_data' => 'confirm_course']
                        ]
                    ]
                ], JSON_THROW_ON_ERROR)
            ];
        }


        // Utilisation de curl pour envoyer une requête POST pour envoyer le message
        $ch = curl_init();
        // L'URL de l'API Telegram pour envoyer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

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
            switch ($route) {
                case "envoi":
                    $text = "⚠️ CONFIRMER LA COURSE ❓";
                    $routeUrl = "confirmation";
                    break;

                case "confirmation":
                    $text = "✅ COURSE TERMINER ❓";
                    $routeUrl = "finished";
                    break;
                default:
                    $text = "";
                    break;
            }

            $url = "https://127.0.0.1:8001/service/" . $routeUrl . "/" . $messageId;


            // Mettre à jour la variable reply_markup avec le lien contenant l'ID du message
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => $text, 'url' => $url]
                    ]
                ]
            ], JSON_THROW_ON_ERROR);

            // Réinitialiser cURL pour modifier le message
            $ch = curl_init();
            $urlEdit = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

            // Préparer les données pour l'édition du message
            $editData = [
                'chat_id' => $chatID,
                'message_id' => $messageId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
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


    public function sendMessageLite($message, $chatID): bool|string
    {

        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // Utilisation de curl pour envoyer une requête POST pour envoyer le message
        $ch = curl_init();

        // Les paramètres à envoyer
        $data = [
            'chat_id' => $chatID,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
        ];


        // Utilisation de curl pour envoyer une requête POST pour envoyer le message
        $ch = curl_init();
        // L'URL de l'API Telegram pour envoyer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

        // Initialiser cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Exécutez la requête et obtenez la réponse
        $response = curl_exec($ch);

        // Fermez la session curl
        curl_close($ch);

        return $response;
    }

    public function updateMessageLite($messageId, $chatID, $updatedMessage): bool|string
    {
        $botToken = "8199822196:AAG7wgYEGZ5rETbYxjvav60fdAUrjpoyJ1Q"; // PickupTransport_bot.

        // L'URL de l'API Telegram pour éditer un message
        $url = "https://api.telegram.org/bot" . $botToken . "/editMessageText";

        // Paramètres à envoyer
        $data = [
            'chat_id' => $chatID,  // Assurez-vous que le chat_id est correctement défini
            'message_id' => $messageId,
            'text' => $updatedMessage,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
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