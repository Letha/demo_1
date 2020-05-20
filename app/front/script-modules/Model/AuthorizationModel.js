class AuthorizationModel
{
    async enter(formDataObject)
    {
        return await this._processPostRequest(formDataObject, '/api/user/entrance/authorization');
    }
    async register(formDataObject)
    {
        return await this._processPostRequest(formDataObject, '/api/user/entrance/registration');
    }
    async exit()
    {
        return await fetch('/api/user/entrance/exit');
    }
    
    async _processPostRequest(formDataObject, uri)
    {
        let fetchResult = await fetch(uri, {
            method: 'POST',
            body: formDataObject
        });
        return fetchResult;
    }
}