//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//

export interface Book {
    id: number
    title: string
    author: string
    position: number
    url: string
    file: number
    colour: string
    pattern: number
    height: number
}

export function constructBook(data: any): Book {
    return {
        id: data.id,
        title: data.title,
        author: data.author,
        position: data.position,
        url: data.url,
        file: data.file,
        colour: data.colour,
        pattern: data.pattern,
        height: data.height,
    }
}
