import api from './api'

export const isAuthenticated = async () => {
    try {
        const { data } = await api.get('/auth_user')
        localStorage.setItem('user', JSON.stringify(data))
        return true
    } catch {
        localStorage.removeItem('user')
        return false
    }
}
