import { getLocalUser } from "./helpers/auth";

const user = getLocalUser();
export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        role: {
            member: 1,
            contributor: 2,
            editor: 3,
            admin: 4,
        },
        errorMessages: {
            overDescLength: '形容が500文字以下み有効です。',
            emptyImage: '発売号画像を入力してください。',
            emptyReleaseNumber: '発売号を入力してください。',
            processErr: '情報処理の中に、エーラが発生しました。',
            emptyTitle: 'タイトルを入力してください。',
            overTitleLength: 'タイトルは10文字から300文字まで入力してください。',
            emptyContentPost: '記事内容を入力してください。',
            emptyCategoryParent: '親カテゴリを入力してください。',
            emptyCategoryChild: '子カテゴリを入力してください。',
            emptyCategory: 'カテゴリ名を入力してください。',
            overCategoryLength: 'カテゴリ名は５文字から５０文字まで入力してください。',
            emptyNameUser: 'ユーザー名を入力してください。',
            overNameUserLength: 'ユーザー名は１００文字以下の有効です。',
            emptyEmail: 'メール を入力してください。',
            emptyPassword: 'パスワードを入力してください。',
            overPasswordLength: 'パスワードは７２文字以下の有効です。',
            overPhoneLength: '携帯電話は１２文字以下の有効です。',
            overAddressLength: '住所は１００文字以下の有効です。'
        }
    },
    mutations: {
        login(state) {
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, { token: payload.access_token });
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        }
    },

    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        }
    },
    actions: {
        login(context) {
            context.commit("login");

        },
        logout(context) {
            context.commit("logout");
        }
    }
};