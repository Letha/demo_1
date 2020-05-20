class InitiationView
{
    initialize()
    {
        this._initializeAreasForBackground();
    }
    
    _initializeAreasForBackground()
    {
        let areasForProfilePhoto = document.querySelectorAll('*[data-function="area-for-background"][data-background-id="profile-photo"]');
        areasForProfilePhoto.forEach(areaForBackground => {
            areaForBackground.style.backgroundImage = "url('/api/user/files/images/profile_photo')";
        });
    }
}