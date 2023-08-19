export const isUserLoggedIn = () => {
  return localStorage.getItem('userData') && localStorage.getItem('accessToken')
}
export const getUserData = () => JSON.parse(localStorage.getItem('userData'))
export const getHomeRouteForLoggedInUser = roles => {
  if(roles.some(role => role.name == 'pd')){
    return {name: 'profil-peserta-didik'}
  }
  return { name: 'dashboard' }
}
