import { setAuthorization } from "./general";

export function login(credentials) {
    return new Promise((res, rej) => {
        axios.post('/auth/login', credentials)
            .then((response) => {
                setAuthorization(response.data.access_token);
                res(response.data);
            })
            .catch(err => {
                if (err.response.status === 401) {
                    rej("Incorrect email or password");
                }
                if (err.response.status === 429) {
                    rej("パスワードに5回連続で誤りがあったため、アカウントをロックしました。管理者に連絡を行ってください。");
                }
            })
    })
}

export function logout() {
    return new Promise((res, rej) => {
        axios.post('/auth/logout')
            .then((response) => {
                console.log(response.data);
            })
            .catch((err) => {
                rej("情報処理の中に、エーラが発生しました。");
            })
    })
}


export function getLocalUser() {
    const userStr = localStorage.getItem("user");
    if (!userStr) {
        return null;
    }
    return JSON.parse(userStr);
}