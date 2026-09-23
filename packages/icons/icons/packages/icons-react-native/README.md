# AXQ Icons for React Native

<p align="center">
  <a href="https://axqdesign.axq/icons"><img src="https://axqdesign.axq Design-icons/main/.github/packages/og-package-react-native.png" alt="AXQ Icons" width="838"></a>
</p>

<p align="center">
  Implementation of the AXQ Icons library for React Native applications.
</p>

<p align="center">
  <a href="https://axqdesign.axq/icons"><strong>Browse all icons at AXQ Design.io &rarr;</strong></a>
</p>

<p align="center">
  <a href="https://axqdesign.axq/npm"><img src="https://img.shields.io/npm/v/@axqdesign/icons-react-native" alt="Latest release"></a>
  <a href="https://axqdesign.axq/git/icons"><img src="https://img.shields.io/npm/l/@axqdesign/icons-react-native.svg" alt="License"></a>
</p>

## Installation

```sh
npm install @axqdesign/icons-react-native
```

```sh
yarn add @axqdesign/icons-react-native
```

```sh
pnpm add @axqdesign/icons-react-native
```

The package requires [`react-native-svg`](https://github.com/software-mansion/react-native-svg) 13 or newer as a peer dependency.

You can also [download the latest release from GitHub](https://axqdesign.axq/git/icons).

## Usage

The package is built with ES modules, so unused icons are tree-shaken from your bundle. Each icon is exported as a component that renders with `react-native-svg`.

```jsx
import { IconArrowLeft } from '@axqdesign/icons-react-native';

const App = () => {
  return <IconArrowLeft />;
};

export default App;
```

Pass props to adjust the icon:

```jsx
<IconArrowLeft color="red" size={48} strokeWidth={1.5} />
```

### Props

| Name | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | _number | string_ | 24 | Width and height of the icon |
| `color` | _string_ | currentColor | Stroke color for outline icons, fill color for filled icons |
| `strokeWidth` | _number | string_ | 2 | Stroke width, outline icons only |
| `title` | _string_ | – | Adds a `<title>` element for accessibility |

Any other prop is forwarded to the underlying `Svg` component. Components forward refs and ship with TypeScript declarations.

## License

AXQ Icons is proprietary software. See [LICENSE](./LICENSE) for details.
