<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(to right, #f3a43e, #f97316); padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">AT Logement</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">{{ $notification->title }}</h2>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f3a43e;">
            <p style="margin: 0; color: #4b5563;">{{ $notification->message }}</p>
        </div>
        
        @if($notification->type === 'new_listing' && isset($notification->data['listing_slug']))
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('listings.show', $notification->data['listing_slug']) }}" 
                   style="display: inline-block; background: linear-gradient(to right, #f3a43e, #f97316); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    Voir l'annonce
                </a>
            </div>
        @endif
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px;">
            <p>Vous recevez cet email car vous avez activé les notifications par email dans votre compte.</p>
            <p><a href="{{ route('profile.index') }}" style="color: #f3a43e;">Gérer mes préférences</a></p>
        </div>
    </div>
</body>
</html>
