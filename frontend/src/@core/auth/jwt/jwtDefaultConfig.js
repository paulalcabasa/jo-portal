export default {
  // Endpoints
  loginEndpoint   : 'api/auth/login',
  refreshEndpoint : 'api/auth/refresh',
  logoutEndpoint  : 'api/auth/logout',
  verifyEndpoint  : 'api/auth/me',

  // This will be prefixed in authorization header with token
  // e.g. Authorization: Bearer <token>
  tokenType: 'Bearer',

  // Value of this property will be used as key to store JWT token in storage
  storageTokenKeyName: process.env.VUE_APP_JWT_ACCESS_TOKEN,
  //storageRefreshTokenKeyName: process.env.VUE_APP_JWT_REFRESH_TOKEN,
}
