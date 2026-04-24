<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre message - AT Logement</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f3a43e 0%, #f97316 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }
        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #f3a43e;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .response-box {
            background-color: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .response-box h3 {
            margin-top: 0;
            color: #f97316;
            font-size: 18px;
        }
        .response-content {
            color: #333;
            white-space: pre-wrap;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #f3a43e 0%, #f97316 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .info-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #0369a1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AT Logement</h1>
        </div>
        
        <div class="greeting">
            <p><strong>Bonjour {{ $messageModel->name ?? 'Client' }},</strong></p>
        </div>
        
        <p>Nous avons bien reçu votre message et nous vous remercions de votre intérêt.</p>
        
        @if($messageModel->listing)
        <div class="info-box">
            <strong>Annonce concernée :</strong> {{ $messageModel->listing->title }}
        </div>
        @endif
        
        <div class="message-box">
            <strong>Votre message :</strong><br>
            <em>{{ $messageModel->message ?? 'Aucun message spécifique' }}</em>
        </div>
        
        <div class="response-box">
            <h3>Notre réponse :</h3>
            <div class="response-content">{{ $response }}</div>
        </div>
        
        <p>Vous pouvez suivre toutes vos demandes et voir cette réponse dans votre espace personnel.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('dashboard') }}" class="button">Accéder à mon espace</a>
        </div>
        
        @if($messageModel->listing)
        <div style="text-align: center; margin-top: 15px;">
            <a href="{{ route('listings.show', $messageModel->listing->slug) }}" style="color: #f97316; text-decoration: none;">
                Voir l'annonce concernée →
            </a>
        </div>
        @endif
        
        <div class="footer">
            <p><strong>Merci de votre confiance !</strong></p>
            <p>L'équipe AT Logement</p>
            <p style="font-size: 12px; color: #9ca3af;">
                Si vous avez d'autres questions, n'hésitez pas à nous contacter.
            </p>
        </div>
    </div>
</body>
</html>
