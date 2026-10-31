export * from './auth';
export * from './navigation';
export * from './ui';
export * from './pagination';
export * from './filters';
export * from './forms';
export * from './media'
export * from './taxonomy';
export * from './seo'
export * from './participant'

export * from './user';
export * from './interview'
export * from './content';
export * from './magazine';
export * from './report';
export * from './event';
export * from './research';
export * from './survey';


export interface Model {
    type: string | number
    id: number
    title: string
}