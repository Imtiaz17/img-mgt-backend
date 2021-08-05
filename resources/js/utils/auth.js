import Cookies from 'js-cookie';

const TokenKey = 'token';

export function getToken() {
    if (Cookies.get(TokenKey)) {
        return Cookies.get(TokenKey);
    } else {
        return null;
    }
}

export function setToken(token) {
    return Cookies.set(TokenKey, token);
}

export function removeToken() {
    return Cookies.remove(TokenKey);
}
