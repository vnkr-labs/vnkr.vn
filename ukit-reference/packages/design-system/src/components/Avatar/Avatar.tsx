import React from 'react';
import styles from './Avatar.module.css';

export type AvatarSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';

export interface AvatarProps {
  src?: string;
  alt?: string;
  /** Fallback initials when no src */
  name?: string;
  size?: AvatarSize;
  /** Show online indicator ring */
  online?: boolean;
  /** Stack group — border ring */
  grouped?: boolean;
  onClick?: () => void;
}

const SIZE_PX: Record<AvatarSize, number> = {
  xs: 24, sm: 32, md: 40, lg: 56, xl: 80,
};

function initials(name: string): string {
  return name
    .split(' ')
    .slice(0, 2)
    .map((w) => w[0]?.toUpperCase() ?? '')
    .join('');
}

/**
 * Avatar — user image or initials fallback.
 */
export function Avatar({ src, alt, name, size = 'md', online, grouped, onClick }: AvatarProps) {
  const px = SIZE_PX[size];
  const label = alt ?? name ?? 'User avatar';
  const Tag = onClick ? 'button' : 'span';

  return (
    <Tag
      className={styles.root}
      data-size={size}
      data-grouped={grouped || undefined}
      style={{ width: px, height: px }}
      onClick={onClick}
      aria-label={onClick ? label : undefined}
      type={onClick ? 'button' : undefined}
    >
      {src ? (
        <img src={src} alt={label} className={styles.img} draggable={false} />
      ) : (
        <span className={styles.initials} aria-hidden="true">
          {name ? initials(name) : '?'}
        </span>
      )}
      {online && (
        <span className={styles.onlineDot} aria-label="Online" role="img" />
      )}
    </Tag>
  );
}

/** AvatarGroup — stacked avatars */
export interface AvatarGroupProps {
  avatars: Pick<AvatarProps, 'src' | 'name' | 'alt'>[];
  max?: number;
  size?: AvatarSize;
}

export function AvatarGroup({ avatars, max = 4, size = 'md' }: AvatarGroupProps) {
  const visible = avatars.slice(0, max);
  const overflow = avatars.length - max;

  return (
    <div className={styles.group} role="group" aria-label={`${avatars.length} avatars`}>
      {visible.map((a, i) => (
        <Avatar key={i} {...a} size={size} grouped />
      ))}
      {overflow > 0 && (
        <span className={styles.overflow} data-size={size} aria-label={`+${overflow} more`}>
          +{overflow}
        </span>
      )}
    </div>
  );
}
