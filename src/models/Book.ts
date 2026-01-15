export interface Book {
    id: number
    user_id: string
    title: string
    author: string
    position: number
    url: string
    file: number
}

export function constructBook(data: any): Book {
    return {
        id: data.id,
        user_id: data.user_id,
        title: data.title,
        author: data.author,
        position: data.position,
        url: data.url,
        file: data.file
    }
}
