import pandas as pd
from sklearn.decomposition import PCA
from sklearn.preprocessing import StandardScaler
import matplotlib.pyplot as plt
import numpy as np


# df = pd.read_sql_table('table_name', 'postgres:///db_name')


def main():
    features = ['sleep quality', 'social interaction', 'exercise', 'goals satisfaction', 'stress']
    target = ['mood']

    df = pd.read_csv("mock.csv")
    x = df.loc[:, features].values
    x = StandardScaler().fit_transform(x)  # mean 0 and variance 1

    # PCA
    pca = PCA(n_components=2)
    prcomp = pca.fit_transform(x)
    prcomp_df = pd.DataFrame(data=prcomp, columns=['PC1', 'PC2'])
    combined_df = pd.concat([prcomp_df, df[target]], axis=1)
    components = pca.components_
    print("Explained variances:")
    print(pca.explained_variance_ratio_)

    fig, ax = plt.subplots()
    fig.set_size_inches(13, 10)

    ax.set_xlabel('PC1', fontsize=15)
    ax.set_ylabel('PC2', fontsize=15)
    ax.set_title('2D projection summary', fontsize=20)

    colors = combined_df['mood'].tolist()

    plt.scatter(combined_df['PC1'], combined_df['PC2'], c=colors, cmap='plasma_r', s=80)
    cbar = plt.colorbar()
    cbar.set_label('Mood')

    # Plotting the vectors corresponding to the old features
    V = components.T

    for i in range(len(V)):
        V[i] = V[i] / np.linalg.norm(V[i])

    origin = np.array([[0, 0, 0, 0, 0], [0, 0, 0, 0, 0]])  # origin point

    plt.quiver(*origin, V[:, 0], V[:, 1], color=['k'], scale=1, scale_units='xy', width=0.003)

    xs = V[:, 0]
    ys = V[:, 1]

    for i, (x, y) in enumerate(zip(xs, ys)):
        label = features[i]
        plt.annotate(label, (x, y), textcoords="offset points", xytext=(0, 1), ha='center')

    plt.savefig("plot.png")


if __name__ == "__main__":
    main()
