export function validateName(name) {
    name = (name ?? '').trim();

    if (! name){
        return 'O nome é obrigatório.';
    }

    if (name.length < 3) {
        return 'O nome deve ter no mínimo 3 caracteres';
    }

    if (name.length > 255) {
        return 'O nome pode ter no máximo 255 caracteres.';
    }

    return null;
}

export function validateEmail(email) {
    email = (email ?? '').trim()

    if (! email) {
        return 'O e-mail é obrigatório.';
    }

    if (email.length > 255) {
        return 'O e-mail pode ter no máximo 255 caracteres.';
    }

    const emailRegex =  /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (! emailRegex.test(email)) {
        return 'Informe um e-mail válido.';
    }

    return null
}

export function validateBio(bio) {
    bio = (bio ?? '').trim()

    if (bio.length > 500) {
        return 'A bio pode ter no máximo 500 caracteres.'
    }

    return null
}

export function validatePassword(password) {
    password = (password ?? '').trim();

    const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/;
    
    if (! password){
        return 'A senha é obrigatória.';
    }

    if (password.length < 8) {
        return 'A senha deve ter pelo menos 8 caracteres.';
    }
    
    if (! passwordRegex.test(password)) {
        return 'A senha deve ter pelo meno uma letra, numero e símbolo.';
    }

    return null;
}

export function validateUsername(username) {
    username = (username ?? '').trim();

    if (!username) {
        return 'O nome de usuário é obrigatório.';
    }

    if (username.length < 3) {
        return 'O nome de usuário deve ter no mínimo 3 caracteres.';
    }

    if (username.length > 30) {
        return 'O nome de usuário pode ter no máximo 30 caracteres.';
    }

    const usernameRegex = /^[A-Za-z0-9._-]+$/;

    if (!usernameRegex.test(username)) {
        return 'O nome de usuário pode conter apenas letras, números, pontos, hífen e underscore.';
    }

    return null;
}

export function confirmPassword(password, passwordConfirmation) {
    if (password !== passwordConfirmation) {
        return 'As senhas precisam ser iguais';
    }

    return null;
}
