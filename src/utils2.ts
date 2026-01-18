import type {Book} from "./models/Book.ts";

export function getPath(book: Book) {
    const img_link = `/index.php/core/preview?fileId=${book.url}&x=190&y=280`
    return(img_link)
}
