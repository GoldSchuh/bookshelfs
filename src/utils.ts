import type {Book} from "./models/Book.ts";

export function getPath(book: Book) {
    return `/index.php/core/preview?fileId=${book.url}&x=190&y=280`
}
export function getRandomHeight(min = 220, max = 290) {
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
    return availableColors[Math.floor(Math.random() * availableColors.length)] || "darkolivegreen";
}
