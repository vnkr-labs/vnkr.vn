export type VNKRIconNode = [elementName: string, attrs: Record<string, string>];
export type VNKRIcon = { name: string, type: 'outline' | 'filled', nodes: VNKRIconNode[] };
export type VNKRIcons = { [key: string]: VNKRIcon };
