import type {Book} from "./models/Book.ts";

export function getPath(book: Book) {
    const img_link = `/index.php/core/preview?fileId=${book.url}&x=190&y=280`
    return(img_link)
}
export function getRandomHeight(min = 220, max = 290) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
export function randomPattern() {
    return getRandomHeight(0, 3);
}
export function randomColor(): string {
    const availableColors = [
        "maroon",
        "darkgreen",
        "darkolivegreen",
        "brown",
        "saddlebrown",
        "sienna",
        "midnightblue",
    ];
    // @ts-ignore
    return availableColors[Math.floor(Math.random() * availableColors.length)];
}
