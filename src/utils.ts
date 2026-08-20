//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//

import type {Book} from "./models/Book.ts";
import { generateUrl } from "@nextcloud/router";

/**
 * Return the preview URL for a book's cover, or null when the book has no
 * cover (its `url` is a file id of the cover; `-1` means "no cover").
 */
export function getPath(book: Book): string | null {
    if (book.url === undefined || book.url === null || book.url === '' || book.url === '-1') {
        return null;
    }
    return generateUrl(`/core/preview?fileId=${book.url}&x=190&y=280`)
}
export function getRandomHeight(min = 220, max = 290) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

export function randomPattern(): number {
    return Math.floor(Math.random() * 4); // 0-3
}
export function randomColour(): string {
    const availableColours = [
        "maroon",
        "darkgreen",
        "darkolivegreen",
        "brown",
        "saddlebrown",
        "sienna",
        "midnightblue",
    ];
    return availableColours[Math.floor(Math.random() * availableColours.length)] || "darkolivegreen";
}
